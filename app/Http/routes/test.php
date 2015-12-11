<?php
/*
 * Test routes
 */

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {
        $json = '
          [
    {
      "name": "护理",

      "children": [
        {
          "name": "洗发"
        },
        {
          "name": "护发"
        },
        {
          "name": "套装"
        },
        {
          "name": "脱毛"
        },
        {
          "name": "发膜"
        },
        {
          "name": "牙膏"
        },
        {
          "name": "淋浴"
        },
        {
          "name": "润肤乳"
        }
      ]
    },
    {
      "name": "彩妆",

      "children": [
        {
          "name": "卸妆"
        },
        {
          "name": "防晒"
        },
        {
          "name": "BB霜"
        },
        {
          "name": "粉饼"
        },
        {
          "name": "发膜"
        },
        {
          "name": "睫毛膏"
        },
        {
          "name": "唇彩"
        },
        {
          "name": "腮红"
        },
        {
          "name": "套装"
        }
      ]
    },
    {
      "name": "香氛",

      "children": [
        {
          "name": "女士香水"
        },
        {
          "name": "男士香水"
        },
        {
          "name": "中性香水"
        },
        {
          "name": "Q版香水"
        },
        {
          "name": "套装"
        }
      ]
    },
    {
      "name": "美妆",

      "children": [
        {
          "name": "雅诗兰蔻"
        },
        {
          "name": "迪奥"
        },
        {
          "name": "海蓝之恋"
        }
      ]
    }
  ]

        ';
        $categories = json_decode($json, true);

        return $categories;

        return view('frontend.auth.register');
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::user()->logout();
        Auth::user()->loginUsingId($id);

        return $id . ' login ' . (Auth::user()->check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });

}
