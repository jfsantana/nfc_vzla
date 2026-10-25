<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PDO;
use PDOException;

final class LandingVisitService
{
    public const LANDINGS = [
        'ghhoteles' => 'GH Hoteles',
        'jsantana' => 'Jesus Santana',
    ];

    public const TIMEZONE = 'America/Caracas';

    private const INITIAL_VIEWS = [
        'ghhoteles' => [
            '2026-10-22' => 19,
            '2026-10-23' => 12,
            '2026-10-24' => 47,
        ],
        'jsantana' => [],
    ];

    private PDO $database;

    public function __construct(?string $databasePath = null)
    {
        $databasePath ??= dirname(__DIR__, 2) . '/storage/analytics/visits.sqlite';
        $this->database = new PDO('sqlite:' . $databasePath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        $this->database->exec(
            'CREATE TABLE IF NOT EXISTS landing_visit_daily (
                landing_id TEXT NOT NULL,
                visit_date TEXT NOT NULL,
                views INTEGER NOT NULL CHECK (views > 0),
                first_visit TEXT NOT NULL,
                last_visit TEXT NOT NULL,
                PRIMARY KEY (landing_id, visit_date)
            )'
        );
    }

    public static function recordRequest(string $landingId): void
    {
        self::validateLanding($landingId);
        header('Cache-Control: no-store, max-age=0');

        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
            return;
        }

        try {
            (new self())->recordVisit($landingId);
        } catch (PDOException $exception) {
            // Una averia del contador no debe interrumpir la landing.
            error_log('[landing-visits][' . $landingId . '] ' . $exception->getMessage());
        }
    }

    public function recordVisit(string $landingId, ?DateTimeImmutable $at = null): void
    {
        self::validateLanding($landingId);
        $at = self::localTime($at);
        $timestamp = $at->format('Y-m-d H:i:s');
        $statement = $this->database->prepare(
            'INSERT INTO landing_visit_daily (landing_id, visit_date, views, first_visit, last_visit)
             VALUES (:landing, :visit_date, 1, :first_visit, :last_visit)
             ON CONFLICT (landing_id, visit_date) DO UPDATE SET
                views = landing_visit_daily.views + 1,
                first_visit = MIN(landing_visit_daily.first_visit, excluded.first_visit),
                last_visit = MAX(landing_visit_daily.last_visit, excluded.last_visit)'
        );
        $statement->execute([
            'landing' => $landingId,
            'visit_date' => $at->format('Y-m-d'),
            'first_visit' => $timestamp,
            'last_visit' => $timestamp,
        ]);
    }

    /**
     * @return array{
     *     total: int, today: int, last7: int, last30: int,
     *     initialViews: array{total: int, today: int, last7: int, last30: int},
     *     recordedTotal: int,
     *     firstVisit: ?string, lastVisit: ?string,
     *     days: array<string, int>
     * }
     */
    public function getStatistics(string $landingId, ?DateTimeImmutable $at = null): array
    {
        self::validateLanding($landingId);
        $today = self::localTime($at)->setTime(0, 0);
        $todayKey = $today->format('Y-m-d');
        $weekStart = $today->modify('-6 days')->format('Y-m-d');
        $monthStart = $today->modify('-29 days')->format('Y-m-d');
        $statistics = [
            'total' => 0,
            'today' => 0,
            'last7' => 0,
            'last30' => 0,
            'initialViews' => ['total' => 0, 'today' => 0, 'last7' => 0, 'last30' => 0],
            'recordedTotal' => 0,
            'firstVisit' => null,
            'lastVisit' => null,
            'days' => [],
        ];

        for ($offset = 29; $offset >= 0; $offset--) {
            $statistics['days'][$today->modify("-{$offset} days")->format('Y-m-d')] = 0;
        }

        $statement = $this->database->prepare(
            'SELECT visit_date, views, first_visit, last_visit
             FROM landing_visit_daily
             WHERE landing_id = :landing AND visit_date <= :today
             ORDER BY visit_date'
        );
        $statement->execute(['landing' => $landingId, 'today' => $todayKey]);

        while ($row = $statement->fetch()) {
            $date = $row['visit_date'];
            $views = (int) $row['views'];
            $statistics['total'] += $views;
            $statistics['firstVisit'] ??= $row['first_visit'];
            $statistics['lastVisit'] = $row['last_visit'];
            if ($date === $todayKey) {
                $statistics['today'] = $views;
            }
            if ($date >= $weekStart) {
                $statistics['last7'] += $views;
            }
            if ($date >= $monthStart) {
                $statistics['last30'] += $views;
                $statistics['days'][$date] = $views;
            }
        }

        $statistics['recordedTotal'] = $statistics['total'];
        foreach (self::INITIAL_VIEWS[$landingId] as $date => $views) {
            if ($date > $todayKey) {
                continue;
            }
            $statistics['initialViews']['total'] += $views;
            if ($date === $todayKey) {
                $statistics['initialViews']['today'] += $views;
            }
            if ($date >= $weekStart) {
                $statistics['initialViews']['last7'] += $views;
            }
            if ($date >= $monthStart) {
                $statistics['initialViews']['last30'] += $views;
                $statistics['days'][$date] += $views;
            }
        }
        foreach ($statistics['initialViews'] as $key => $views) {
            $statistics[$key] += $views;
        }

        return $statistics;
    }

    private static function localTime(?DateTimeImmutable $at): DateTimeImmutable
    {
        $timezone = new DateTimeZone(self::TIMEZONE);
        return ($at ?? new DateTimeImmutable('now', $timezone))->setTimezone($timezone);
    }

    private static function validateLanding(string $landingId): void
    {
        if (!isset(self::LANDINGS[$landingId])) {
            throw new InvalidArgumentException('Landing no reconocida: ' . $landingId);
        }
    }
}
