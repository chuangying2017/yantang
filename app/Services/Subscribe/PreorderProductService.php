<?php namespace App\Services\Subscribe;

use App\Models\Subscribe\PreorderProductSku;
use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract;
use App\Repositories\Subscribe\PreorderProductSku\PreorderProductSkuRepositoryContract;
use App\Services\Subscribe\PreorderProtocol;
use StaffService;

/**
 * Class Access
 * @package App\Services\Access
 */
class PreorderProductService
{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    private $preorder_product;
    private $product_sku;
    private $preorderRepo;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(PreorderProductRepositoryContract $preorder_product, PreorderProductSkuRepositoryContract $product_sku, PreorderRepositoryContract $preorderRepo)
    {
        $this->preorder_product = $preorder_product;
        $this->product_sku = $product_sku;
        $this->preorderRepo = $preorderRepo;
    }

    public function batchOperation($input = [])
    {
        $weekdays = json_decode($input['weekdays'], true);
        $preorder_product = [];
        foreach ($weekdays as $key => $weekday) {
            $date = [
                'preorder_id' => $input['preorder_id'],
                'daytime' => $weekday['daytime'],
                'weekday' => $key,
                'skus' => $weekday['skus'],
            ];
            $id = null;
            if (!empty($weekday['id'])) {
                $id = $weekday['id'];
            }
            $preorder_product[] = $this->operation($date, $id);
        }

        return collect($preorder_product);
    }

    public function operation($input = [], $id = null)
    {
        $preorder_product = $this->preorder_product;
        $skus = array_get($input, 'skus');
        unset($input['skus']);

        if (empty($id)) {
            $preorder_product = $preorder_product->create($input);
            //preorder status 更新为 no_staff
            $this->preorderRepo->update(['status' => PreorderProtocol::STATUS_OF_NO_STAFF], $input['preorder_id']);
        } else {
            $preorder_product = $preorder_product->update($input, $id);
            $preorder = $this->preorderRepo->byId($input['preorder_id'], ['staffPreorder']);
            if (!empty($preorder->staffPreorder->staff_id)) {
                //更新staff_weekly
                StaffService::updateStaffWeekly($input['preorder_id']);
            }
        }

        foreach ($skus as $sku_id => $value) {
            if (!empty($id)) {
                $product_sku = $this->product_sku;
                $product_sku->delete($preorder_product->id);
            }
            $value['sku_id'] = $sku_id;
            $value['pre_product_id'] = $preorder_product->id;
            $preorder_product_sku[] = new PreorderProductSku($value);
        }
        $sku = $preorder_product->sku()->saveMany($preorder_product_sku);

        $preorder_product->preorder_product_sku = $sku;

        return $preorder_product;
    }

}
