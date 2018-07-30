define({ "api": [
  {
    "type": "get",
    "url": "/admin/integral/freedomThe/{id}/shippingOrderDetail",
    "title": "发货详情",
    "name": "GetIntegral",
    "group": "Integral",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "arrayName",
            "description": "<p>请求成功就是一个数据集数组或者空的数组.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "src/api.php",
    "groupTitle": "Integral"
  },
  {
    "type": "get",
    "url": "/admin/integral/freedomThe/shippingManagement",
    "title": "发货管理",
    "name": "GetManager",
    "group": "Integral",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键字</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "arrayName",
            "description": "<p>请求成功就是一个数据集数组或者空的数组.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "src/api.php",
    "groupTitle": "Integral"
  }
] });
