<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13/013
 * Time: 11:17
 */

namespace App\Repositories\Comment;


class CommentProtocol
{

    const COMMENT_STATUS_IS_NOT_USES = 'ToBeUsed';

    const COMMENT_STATUS_IS_USES = 'HaveUses';

    const COMMENT_STATUS_IS_ADDITIONAL = 'Additional'; // additional comments

    const COMMENT_STATION = 'station_id';

    const COMMENT_STAFF = 'staff_id';
}