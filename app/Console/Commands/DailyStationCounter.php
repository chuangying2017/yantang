<?php

namespace App\Console\Commands;

use App\Models\Counter\MonthlyCounter;
use App\Models\Counter\WeeklyCounter;
use App\Models\Counter\YearlyCounter;
use App\Repositories\Counter\StaffOrderCounterRepo;
use App\Repositories\Counter\StationOrderCounterRepo;
use App\Repositories\Counter\Unit\DailyCounterRepository;
use App\Repositories\Counter\Unit\MonthlyCounterRepository;
use App\Repositories\Counter\Unit\WeeklyCounterRepository;
use App\Repositories\Counter\Unit\YearlyCounterRepository;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use Illuminate\Console\Command;

class DailyStationCounter extends Command {

    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;
    /**
     * @var StaffRepositoryContract
     */
    private $staffRepo;
    /**
     * @var StationOrderCounterRepo
     */
    private $stationOrderCounterRepo;
    /**
     * @var StaffOrderCounterRepo
     */
    private $staffOrderCounterRepo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'counter:daily-station';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日常服务部计数器';

    /**
     * Create a new command instance.
     *
     * @param StationRepositoryContract $stationRepo
     * @param StaffRepositoryContract $staffRepo
     * @param StationOrderCounterRepo $stationOrderCounterRepo
     * @param StaffOrderCounterRepo $staffOrderCounterRepo
     * @param DailyCounterRepository $dailyCounterRepository
     * @param WeeklyCounterRepository $weeklyCounterRepository
     * @param MonthlyCounterRepository $monthlyCounterRepository
     * @param YearlyCounterRepository $yearlyCounterRepository
     */
    public function __construct(
        StationRepositoryContract $stationRepo,
        StaffRepositoryContract $staffRepo,
        StationOrderCounterRepo $stationOrderCounterRepo,
        StaffOrderCounterRepo $staffOrderCounterRepo,
        DailyCounterRepository $dailyCounterRepository,
        WeeklyCounterRepository $weeklyCounterRepository,
        MonthlyCounterRepository $monthlyCounterRepository,
        YearlyCounterRepository $yearlyCounterRepository
    )
    {
        parent::__construct();
        $this->stationRepo = $stationRepo;
        $this->staffRepo = $staffRepo;
        $this->stationOrderCounterRepo = $stationOrderCounterRepo;
        $this->staffOrderCounterRepo = $staffOrderCounterRepo;

        $this->dailyCounterRepository = $dailyCounterRepository;
        $this->weeklyCounterRepository = $weeklyCounterRepository;
        $this->monthlyCounterRepository = $monthlyCounterRepository;
        $this->yearlyCounterRepository = $yearlyCounterRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stations = $this->stationRepo->getAllActive();

        foreach ($stations as $station) {
            $station_quantity = 0;
            $staffs = $station->staffs()->where('status', 1)->get();

            $station_counter = $this->stationOrderCounterRepo->getCounter($station['id'], true);
            foreach ($staffs as $staff) {
                $staff_counter = $this->staffOrderCounterRepo->getCounter($staff['id'], true);

                $preorders = $staff->preorders()->whereIn('status', ['shipping', 'done'])->where('confirm_at', '>=', $staff_counter['updated_at'])->get();

                $staff_quantity = $preorders->count();

                $this->staffOrderCounterRepo->increment($staff_counter, $staff_quantity, 0, false);

                foreach ($preorders as $preorder) {
                    $this->dailyCounterRepository->calUnitCounter($staff_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->weeklyCounterRepository->calUnitCounter($staff_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($staff_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($staff_counter['id'], 1, 0, true, $preorder['confirm_at']);

                    $this->dailyCounterRepository->calUnitCounter($station_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->weeklyCounterRepository->calUnitCounter($station_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($station_counter['id'], 1, 0, true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($station_counter['id'], 1, 0, true, $preorder['confirm_at']);
                }

                $station_quantity += $staff_quantity;
            }

            $this->stationOrderCounterRepo->increment($station_counter, $station_quantity, 0, false);
        }
    }


    /**
     * @var DailyCounterRepository
     */
    private $dailyCounterRepository;
    /**
     * @var WeeklyCounterRepository
     */
    private $weeklyCounterRepository;
    /**
     * @var MonthlyCounterRepository
     */
    private $monthlyCounterRepository;
    /**
     * @var YearlyCounterRepository
     */
    private $yearlyCounterRepository;

}
