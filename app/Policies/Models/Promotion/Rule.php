<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model {

    protected $table = 'promotion_rules';

    protected $guarded = ['id'];


    /**==========================================================
     * Relations
     * ==========================================================*/
    public function qualifies()
    {
        return $this->hasMany(RuleQualify::class, 'rule_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(RuleItem::class, 'rule_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsToMany(Coupon::class, 'promotion_rule', 'rule_id', 'promotion_id');
    }

    public function campaign()
    {
        return $this->belongsToMany(Campaign::class, 'promotion_rule', 'rule_id', 'promotion_id');
    }

    /**==========================================================
     * Attributes
     * ==========================================================*/
    public function setQuaContentAttribute($value)
    {
        if (is_array($value)) {
            if (count($value) > 512) {
                $this->attributes['qua_content'] = null;
            } else {
                $this->attributes['qua_content'] = json_encode($value);
            }
        } else {
            $this->attributes['qua_content'] = $value;
        }
    }

    public function getQuaContentAttribute()
    {
        return json_decode($this->attributes['qua_content']);
    }

    public function setItemContentAttribute($value)
    {
        if (is_array($value)) {
            if (count($value) > 512) {
                $this->attributes['item_content'] = null;
            } else {
                $this->attributes['item_content'] = json_encode($value);
            }
        } else {
            $this->attributes['item_content'] = $value;
        }
    }

    public function getItemContentAttribute()
    {
        return json_decode($this->attributes['item_content']);
    }
}
