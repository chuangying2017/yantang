<?php
/**
 * Created by PhpStorm.
 * User: 张伟
 * Date: 2018/6/16
 * Time: 11:45
 */

namespace App\Services\page;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\Presenter;

class PageRewrite implements LengthAwarePaginator
{

    /**
     * Determine the total number of items in the data store.
     *
     * @return int
     */
    public function total()
    {
        // TODO: Implement total() method.
    }

    /**
     * Get the page number of the last available page.
     *
     * @return int
     */
    public function lastPage()
    {
        // TODO: Implement lastPage() method.
    }

    /**
     * Get the URL for a given page.
     *
     * @param  int $page
     * @return string
     */
    public function url($page)
    {
        // TODO: Implement url() method.
    }

    /**
     * Add a set of query string values to the paginator.
     *
     * @param  array|string $key
     * @param  string|null $value
     * @return $this
     */
    public function appends($key, $value = null)
    {
        // TODO: Implement appends() method.
    }

    /**
     * Get / set the URL fragment to be appended to URLs.
     *
     * @param  string|null $fragment
     * @return $this|string
     */
    public function fragment($fragment = null)
    {
        // TODO: Implement fragment() method.
    }

    /**
     * The the URL for the next page, or null.
     *
     * @return string|null
     */
    public function nextPageUrl()
    {
        // TODO: Implement nextPageUrl() method.
    }

    /**
     * Get the URL for the previous page, or null.
     *
     * @return string|null
     */
    public function previousPageUrl()
    {
        // TODO: Implement previousPageUrl() method.
    }

    /**
     * Get all of the items being paginated.
     *
     * @return array
     */
    public function items()
    {
        // TODO: Implement items() method.
    }

    /**
     * Get the "index" of the first item being paginated.
     *
     * @return int
     */
    public function firstItem()
    {
        // TODO: Implement firstItem() method.
    }

    /**
     * Get the "index" of the last item being paginated.
     *
     * @return int
     */
    public function lastItem()
    {
        // TODO: Implement lastItem() method.
    }

    /**
     * Determine how many items are being shown per page.
     *
     * @return int
     */
    public function perPage()
    {
        // TODO: Implement perPage() method.
    }

    /**
     * Determine the current page being paginated.
     *
     * @return int
     */
    public function currentPage()
    {
        // TODO: Implement currentPage() method.
    }

    /**
     * Determine if there are enough items to split into multiple pages.
     *
     * @return bool
     */
    public function hasPages()
    {
        // TODO: Implement hasPages() method.
    }

    /**
     * Determine if there is more items in the data store.
     *
     * @return bool
     */
    public function hasMorePages()
    {
        // TODO: Implement hasMorePages() method.
    }

    /**
     * Determine if the list of items is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    /**
     * Render the paginator using a given Presenter.
     *
     * @param  \Illuminate\Contracts\Pagination\Presenter|null $presenter
     * @return string
     */
    public function render(Presenter $presenter = null)
    {
        // TODO: Implement render() method.
    }
}