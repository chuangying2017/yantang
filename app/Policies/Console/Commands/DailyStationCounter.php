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
            $station_counter = $this->stationOrderCounterRepo->getCounter($station['id'], true);

            $station_quantity = 0;
            $station_cancel_quantity = 0;
            $station_amount = 0;
            $station_cancel_amount = 0;
            $staffs = $station->staffs()->where('status', 1)->get();

            //cal station user kpi
            $station_user_count = $station->preorder()->whereIn('status', ['shipping', 'done'])->distinct('user_id')->count('user_id');
            $station_counter->setUserCount($station_user_count);
            echo "{$station['name']} user count is  {$station_user_count} \n";

            foreach ($staffs as $staff) {
                $staff_counter = $this->staffOrderCounterRepo->getCounter($staff['id'], true);

                //cal staff user kpi
                $staff_user_count = $staff->preorders()->whereIn('status', ['shipping', 'done'])->distinct('user_id')->count('user_id');
                $staff_counter->setUserCount($staff_user_count);

                $preorders = $staff->preorders()->whereIn('status', ['shipping', 'done'])->where('confirm_at', '>=', $staff_counter['updated_at'])->get();

                foreach ($preorders as $preorder) {
//                    $this->dailyCounterRepository->calUnitCounter($staff_counter['id'], 1, $preorder['total_amount'], true, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($staff_counter['id'], 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);

//                    $this->dailyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                }

                $staff_quantity = $preorders->count();
                $staff_amount = $preorders->sum('total_amount');
                $station_quantity += $staff_quantity;
                $station_amount += $staff_amount;

                $this->staffOrderCounterRepo->increment($staff_counter, $staff_quantity, $staff_amount, false);

                //取消订单,接错重派赞不考虑
                $cancel_preorders = $staff->preorders()->whereIn('status', ['cancel'])->where('updated_at', '>=', $staff_counter['updated_at'])->get();

                foreach ($cancel_preorders as $preorder) {
                    $this->dailyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);

                    $this->dailyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], false, $preorder['confirm_at']);
                }

                $staff_cancel_quantity = $cancel_preorders->count();
                $station_cancel_quantity += $staff_cancel_quantity;
                $staff_cancel_amount = $preorders->sum('total_amount');
                $station_cancel_amount += $staff_cancel_amount;

                $this->staffOrderCounterRepo->decrement($staff_counter, $station_cancel_quantity, $staff_cancel_amount, false);

                echo "{$staff['name']} quantity is  {$staff_quantity} \n";
                echo "{$staff['name']} amount is  {$staff_amount} \n";
            }

            $this->stationOrderCounterRepo->increment($station_counter, $station_quantity, $station_amount, false);
            $this->stationOrderCounterRepo->decrement($station_counter, $station_cancel_quantity, $station_cancel_amount, false);

            echo "{$station['name']} quantity is  {$station_quantity} \n";
            echo "{$station['name']} amount is  {$station_amount} \n";

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
