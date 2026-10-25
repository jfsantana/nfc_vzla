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

    public function testEmptyStatisticsContainThirtyZeroDays(): void
    {
        $service = new LandingVisitService(':memory:');
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 12:00:00'));

        self::assertSame(0, $stats['total']);
        self::assertSame(0, $stats['today']);
        self::assertSame(0, $stats['last7']);
        self::assertSame(0, $stats['last30']);
        self::assertNull($stats['firstVisit']);
        self::assertNull($stats['lastVisit']);
        self::assertCount(30, $stats['days']);
        self::assertSame('2026-09-26', array_key_first($stats['days']));
        self::assertSame('2026-10-25', array_key_last($stats['days']));
        self::assertSame(0, array_sum($stats['days']));
    }

    public function testReloadsCountAndLandingsRemainIsolated(): void
    {
        $service = new LandingVisitService(':memory:');
        $now = $this->at('2026-10-25 12:00:00');
        $service->recordVisit('ghhoteles', $now);
        $service->recordVisit('ghhoteles', $now);
        $service->recordVisit('jsantana', $now);

        $hotel = $service->getStatistics('ghhoteles', $now);
        self::assertSame(2, $hotel['total']);
        self::assertSame(2, $hotel['today']);
        self::assertSame(1, $service->getStatistics('jsantana', $now)['total']);
        self::assertSame($hotel, $service->getStatistics('ghhoteles', $now));
    }

    public function testCalendarWindowsIncludeTodayAndExcludeOutsideDays(): void
    {
        $service = new LandingVisitService(':memory:');
        foreach (['2026-09-25', '2026-09-26', '2026-10-18', '2026-10-19', '2026-10-25'] as $day) {
            $service->recordVisit('ghhoteles', $this->at($day . ' 12:00:00'));
        }
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 13:00:00'));

        self::assertSame(5, $stats['total']);
        self::assertSame(1, $stats['today']);
        self::assertSame(2, $stats['last7']);
        self::assertSame(4, $stats['last30']);
        self::assertSame(4, array_sum($stats['days']));
        self::assertSame(0, $stats['days']['2026-10-24']);
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

    public function testOutOfOrderWritesPreserveFirstAndLastVisit(): void
    {
        $service = new LandingVisitService(':memory:');
        foreach (['12:00:00', '11:00:00', '13:00:00'] as $time) {
            $service->recordVisit('ghhoteles', $this->at('2026-10-25 ' . $time));
        }
        $stats = $service->getStatistics('ghhoteles', $this->at('2026-10-25 14:00:00'));

        self::assertSame(3, $stats['total']);
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
            self::assertSame(1, $reader->getStatistics('ghhoteles', $now)['total']);
        } finally {
            unset($reader, $writer);
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
            self::assertSame(100, $reader->getStatistics('ghhoteles', $now)['total']);
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
