<?php

declare(strict_types=1);

use App\Services\LandingVisitService;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/app/Services/LandingVisitService.php';

final class LandingVisitServiceTest extends TestCase
{
    private function at(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date, new DateTimeZone(LandingVisitService::TIMEZONE));
    }

    public function testHistoricalDistributionContainsExactlySeventyEightViews(): void
    {
        $service = new LandingVisitService(':memory:');
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 12:00:00'));

        self::assertSame(78, $stats['total']);
        self::assertSame(['total' => 78, 'today' => 0, 'last7' => 78, 'last30' => 78], $stats['initialViews']);
        self::assertSame(0, $stats['recordedTotal']);
        self::assertSame(0, $stats['today']);
        self::assertSame(78, $stats['last7']);
        self::assertSame(78, $stats['last30']);
        self::assertNull($stats['firstVisit']);
        self::assertNull($stats['lastVisit']);
        self::assertCount(30, $stats['days']);
        self::assertSame('2026-09-26', array_key_first($stats['days']));
        self::assertSame('2026-10-25', array_key_last($stats['days']));
        self::assertSame(78, array_sum($stats['days']));
        self::assertSame(19, $stats['days']['2026-10-22']);
        self::assertSame(12, $stats['days']['2026-10-23']);
        self::assertSame(47, $stats['days']['2026-10-24']);
        self::assertSame(27, count(array_filter($stats['days'], static fn (int $views): bool => $views === 0)));
    }

    public function testReloadsCountAndLandingsRemainIsolated(): void
    {
        $service = new LandingVisitService(':memory:');
        $now = $this->at('2026-10-25 12:00:00');
        $service->recordVisit('ghhoteles', $now);
        $service->recordVisit('ghhoteles', $now);
        $service->recordVisit('jsantana', $now);

        $hotel = $service->getStatistics('ghhoteles', $now);
        self::assertSame(80, $hotel['total']);
        self::assertSame(2, $hotel['recordedTotal']);
        self::assertSame(80, $hotel['last7']);
        self::assertSame(80, $hotel['last30']);
        self::assertSame(2, $hotel['today']);
        $personal = $service->getStatistics('jsantana', $now);
        self::assertSame(['total' => 0, 'today' => 0, 'last7' => 0, 'last30' => 0], $personal['initialViews']);
        foreach (['total', 'today', 'last7', 'last30', 'recordedTotal'] as $key) {
            self::assertSame(1, $personal[$key]);
        }
        self::assertSame($hotel, $service->getStatistics('ghhoteles', $now));
    }

    public function testCalendarWindowsIncludeTodayAndExcludeOutsideDays(): void
    {
        $service = new LandingVisitService(':memory:');
        foreach (['2026-09-25', '2026-09-26', '2026-10-18', '2026-10-19', '2026-10-25'] as $day) {
            $service->recordVisit('ghhoteles', $this->at($day . ' 12:00:00'));
        }
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 13:00:00'));

        self::assertSame(83, $stats['total']);
        self::assertSame(5, $stats['recordedTotal']);
        self::assertSame(1, $stats['today']);
        self::assertSame(80, $stats['last7']);
        self::assertSame(82, $stats['last30']);
        self::assertSame(82, array_sum($stats['days']));
        self::assertSame(47, $stats['days']['2026-10-24']);
        self::assertSame('2026-09-25 12:00:00', $stats['firstVisit']);
        self::assertSame('2026-10-25 12:00:00', $stats['lastVisit']);
    }

    public function testVenezuelaMidnightAndYearBoundary(): void
    {
        $service = new LandingVisitService(':memory:');
        $service->recordVisit('jsantana', new DateTimeImmutable('2027-01-01T03:59:59+00:00'));
        $service->recordVisit('jsantana', new DateTimeImmutable('2027-01-01T04:00:00+00:00'));
        $stats = $service->getStatistics('jsantana', $this->at('2027-01-01 12:00:00'));

        self::assertSame(2, $stats['total']);
        self::assertSame(1, $stats['today']);
        self::assertSame(1, $stats['days']['2026-12-31']);
        self::assertSame(1, $stats['days']['2027-01-01']);
        self::assertSame('2026-12-31 23:59:59', $stats['firstVisit']);
        self::assertSame('2027-01-01 00:00:00', $stats['lastVisit']);
    }

    public function testInitialViewsRemainOnlyInTotalAfterBothWindowsExpire(): void
    {
        $service = new LandingVisitService(':memory:');
        $service->recordVisit('ghhoteles', $this->at('2026-10-25 12:00:00'));
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-11-25 12:00:00'));

        self::assertSame(79, $stats['total']);
        self::assertSame(1, $stats['recordedTotal']);
        self::assertSame(['total' => 78, 'today' => 0, 'last7' => 0, 'last30' => 0], $stats['initialViews']);
        self::assertSame(0, $stats['last7']);
        self::assertSame(0, $stats['last30']);
        self::assertSame(0, $stats['today']);
        self::assertSame(0, array_sum($stats['days']));
        self::assertSame('2026-10-25 12:00:00', $stats['firstVisit']);
        self::assertSame('2026-10-25 12:00:00', $stats['lastVisit']);
    }

    public function testPersonalLandingStillStartsWithZeroVisits(): void
    {
        $service = new LandingVisitService(':memory:');
        $stats = $service->getStatistics('jsantana', $this->at('2026-10-25 12:00:00'));

        foreach (['total', 'today', 'last7', 'last30', 'recordedTotal'] as $key) {
            self::assertSame(0, $stats[$key]);
        }
        self::assertSame(['total' => 0, 'today' => 0, 'last7' => 0, 'last30' => 0], $stats['initialViews']);
        self::assertSame(0, array_sum($stats['days']));
    }

    public function testHistoricalDaysExpireIndividuallyFromBothWindows(): void
    {
        $service = new LandingVisitService(':memory:');
        foreach ([
            ['2026-10-28', 78, 78],
            ['2026-10-29', 59, 78],
            ['2026-10-30', 47, 78],
            ['2026-10-31', 0, 78],
            ['2026-11-20', 0, 78],
            ['2026-11-21', 0, 59],
            ['2026-11-22', 0, 47],
            ['2026-11-23', 0, 0],
        ] as [$date, $last7, $last30]) {
            $stats = $service->getStatistics('ghhoteles', $this->at($date . ' 12:00:00'));
            self::assertSame(78, $stats['total'], $date);
            self::assertSame(0, $stats['today'], $date);
            self::assertSame($last7, $stats['last7'], $date);
            self::assertSame($last30, $stats['last30'], $date);
            self::assertSame($last7, $stats['initialViews']['last7'], $date);
            self::assertSame($last30, $stats['initialViews']['last30'], $date);
            self::assertSame($last30, array_sum($stats['days']), $date);
        }
    }

    public function testHistoricalAdjustmentsDoNotAppearBeforeTheirDates(): void
    {
        $service = new LandingVisitService(':memory:');
        $before = $service->getStatistics('ghhoteles', $this->at('2026-10-21 12:00:00'));
        self::assertSame(0, $before['total']);
        self::assertSame(0, array_sum($before['days']));

        $firstDay = $service->getStatistics('ghhoteles', $this->at('2026-10-22 12:00:00'));
        self::assertSame(19, $firstDay['total']);
        self::assertSame(19, $firstDay['today']);
        self::assertSame(19, $firstDay['days']['2026-10-22']);
    }

    public function testHistoricalAdjustmentAddsToExistingDailyVisitsWithoutReplacingThem(): void
    {
        $service = new LandingVisitService(':memory:');
        $service->recordVisit('ghhoteles', $this->at('2026-10-24 12:00:00'));
        $service->recordVisit('ghhoteles', $this->at('2026-10-25 12:00:00'));
        $now = $this->at('2026-10-25 13:00:00');
        $stats = $service->getStatistics('ghhoteles', $now);

        self::assertSame(80, $stats['total']);
        self::assertSame(2, $stats['recordedTotal']);
        self::assertSame(1, $stats['today']);
        self::assertSame(48, $stats['days']['2026-10-24']);
        self::assertSame(80, array_sum($stats['days']));
        self::assertSame($stats, $service->getStatistics('ghhoteles', $now));
    }

    public function testOutOfOrderWritesPreserveFirstAndLastVisit(): void
    {
        $service = new LandingVisitService(':memory:');
        foreach (['12:00:00', '11:00:00', '13:00:00'] as $time) {
            $service->recordVisit('ghhoteles', $this->at('2026-10-25 ' . $time));
        }
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 14:00:00'));

        self::assertSame(81, $stats['total']);
        self::assertSame(3, $stats['recordedTotal']);
        self::assertSame('2026-10-25 11:00:00', $stats['firstVisit']);
        self::assertSame('2026-10-25 13:00:00', $stats['lastVisit']);
    }

    public function testCountsPersistAcrossConnections(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'nfc-visits-');
        self::assertNotFalse($path);
        $now = $this->at('2026-10-25 12:00:00');
        try {
            $writer = new LandingVisitService($path);
            $writer->recordVisit('ghhoteles', $now);
            unset($writer);
            $reader = new LandingVisitService($path);
            $stats = $reader->getStatistics('ghhoteles', $now);
            self::assertSame(79, $stats['total']);
            self::assertSame(1, $stats['recordedTotal']);
            self::assertSame($stats, $reader->getStatistics('ghhoteles', $now));
            $database = new PDO('sqlite:' . $path);
            self::assertSame(1, (int) $database->query('SELECT SUM(views) FROM landing_visit_daily')->fetchColumn());
        } finally {
            unset($database, $reader, $writer);
            unlink($path);
        }
    }

    public function testUnknownLandingCannotWrite(): void
    {
        $service = new LandingVisitService(':memory:');
        $this->expectException(InvalidArgumentException::class);
        $service->recordVisit('../another-landing');
    }

    public function testConcurrentWritersDoNotLoseVisits(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'nfc-visits-');
        self::assertNotFalse($path);
        $workers = [];
        $code = 'require $argv[1]; $service = new \App\Services\LandingVisitService($argv[2]);'
            . 'for ($i = 0; $i < 25; $i++) { $service->recordVisit("ghhoteles",'
            . 'new \DateTimeImmutable("2026-10-25T12:00:00-04:00")); }';

        try {
            for ($worker = 0; $worker < 4; $worker++) {
                $process = proc_open([
                    PHP_BINARY, '-d', 'display_startup_errors=0', '-d', 'log_errors=0',
                    '-r', $code, dirname(__DIR__) . '/app/Services/LandingVisitService.php', $path,
                ], [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
                self::assertIsResource($process);
                fclose($pipes[0]);
                $workers[] = [$process, $pipes[1], $pipes[2]];
            }

            foreach ($workers as [$process, $stdout, $stderr]) {
                $output = stream_get_contents($stdout) . stream_get_contents($stderr);
                fclose($stdout);
                fclose($stderr);
                self::assertSame(0, proc_close($process), $output);
            }

            $reader = new LandingVisitService($path);
            $now = $this->at('2026-10-25 12:00:00');
            $stats = $reader->getStatistics('ghhoteles', $now);
            self::assertSame(178, $stats['total']);
            self::assertSame(100, $stats['recordedTotal']);
            self::assertSame(100, $stats['today']);
            self::assertSame(0, $reader->getStatistics('jsantana', $now)['total']);
        } finally {
            foreach ($workers as [$process, $stdout, $stderr]) {
                foreach ([$stdout, $stderr] as $pipe) {
                    if (is_resource($pipe)) {
                        fclose($pipe);
                    }
                }
                if (is_resource($process)) {
                    proc_terminate($process);
                    proc_close($process);
                }
            }
            unset($reader);
            unlink($path);
        }
    }

    public function testUnknownLandingCannotRead(): void
    {
        $service = new LandingVisitService(':memory:');
        $this->expectException(InvalidArgumentException::class);
        $service->getStatistics('unknown');
    }

    public function testUnavailableStorageThrowsInsteadOfReturningZero(): void
    {
        $this->expectException(PDOException::class);
        new LandingVisitService(__FILE__ . '/visits.sqlite');
    }
}
