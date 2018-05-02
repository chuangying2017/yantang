<?php

namespace App\Console\Commands;

use App\Repositories\Counter\StaffOrderCounterRepo;
use App\Repositories\Counter\StationOrderCounterRepo;
use App\Repositories\Counter\Unit\DailyCounterRepository;
use App\Repositories\Counter\Unit\MonthlyCounterRepository;
use App\Repositories\Counter\Unit\WeeklyCounterRepository;
use App\Repositories\Counter\Unit\YearlyCounterRepository;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InitStationCounter extends Command {

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
    protected $signature = 'counter:init-station';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化服务部计数器';

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
            $station_counter = $this->stationOrderCounterRepo->createCounter($station['id'], $station['name']);

            //cal station user kpi
            $station_user_count = $station->preorder()->whereIn('status', ['shipping', 'done'])->distinct('user_id')->count('user_id');
            $station_counter->setUserCount($station_user_count);
            
            echo "{$station['name']} user count is  {$station_user_count} \n";

            $station_quantity = 0;
            $station_amount = 0;
            $staffs = $station->staffs()->where('status', 1)->get();

            foreach ($staffs as $staff) {
                $staff_counter = $this->staffOrderCounterRepo->createCounter($staff['id'], $station['name'] . '|' . $staff['name']);

                //cal staff user kpi
                $staff_user_count = $staff->preorders()->whereIn('status', ['shipping', 'done'])->distinct('user_id')->count('user_id');
                $staff_counter->setUserCount($staff_user_count);
                echo "{$staff['name']} user count is  {$staff_user_count} \n";


                $preorders = $staff->preorders()->whereIn('status', ['shipping', 'done'])->get();
                $staff_quantity = $preorders->count();
                $staff_amount = $preorders->sum('total_amount');

                $staff_counter->setQuantity($staff_quantity);
                $staff_counter->setAmount($staff_amount);
                
                foreach ($preorders as $preorder) {
                    $this->dailyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($staff_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);

                    $this->dailyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
//                    $this->weeklyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->monthlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                    $this->yearlyCounterRepository->calUnitCounter($station_counter, 1, $preorder['total_amount'], true, $preorder['confirm_at']);
                }
                $station_quantity += $staff_quantity;
                $station_amount += $staff_amount;

                echo "{$staff['name']} quantity is  {$staff_quantity} \n";
                echo "{$staff['name']} amount is  {$staff_amount} \n";

            }

            $station_counter->setAmount($station_amount);
            $station_counter->setQuantity($station_quantity);
            
            echo "{$station['name']} quantity is  {$station_quantity} \n";
            echo "{$station['name']} amount is  {$station_amount} \n";
        }
    }

    /**
     * @var DailyCounterRepository
     */
    private $dailyCounterRepository;
    protected $weeklyCounterRepository;
    protected $monthlyCounterRepository;
    protected $yearlyCounterRepository;


}
