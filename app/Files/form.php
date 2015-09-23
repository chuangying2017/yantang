<?php


Form::macro('adminOpen', function($action, $method = 'POST'){

	$result = '
	<form action="'. $action .'" method="POST"  class="am-form am-form-horizontal">
	';
	 $result .= '<input type="hidden" value="' . csrf_token() .'" name="_token" >';
	 $result .= ($method == "UPDATE" ? '<input type="hidden" value="PUT" name="_method" >' : '');

	 return $result;
});


Form::macro('adminInput', function($name, $label = '', $type = 'text', $id = '', $class = [])
{


	$field = '<div class="am-form-group">';
	$field .= $label ? '<label for="user-weibo" class="am-u-sm-3 am-form-label"> ' . $label . '</label>' : '';
	
	$field .=   
	  '<div class="am-u-sm-9">
	    <input type="' . $type . '" name="' . $name . '" value="' . old($name) . '" >
	  </div>';

	$field .= '</div>';

	return $field;
});


Form::macro('adminInputEdit', function($model, $name, $label = '', $type = 'text', $id = '', $class = [])
{

	$field = '<div class="am-form-group">';
	$field .= $label ? '<label for="user-weibo" class="am-u-sm-3 am-form-label"> ' . $label . '</label>' : '';
	
	$field .=   
	  '<div class="am-u-sm-9">
	    <input type="' . $type . '" name="' . $name . '" value="' . (old($name) ? : $model[$name]) . '" >
	  </div>';

	$field .= '</div>';

	return $field;
});

Form::macro('adminSubmit', function($text = '提交')
{



	$field = '
				<div class="am-form-group">
          <div class="am-u-sm-9 am-u-sm-push-3">
            <button type="submit" class="am-btn am-btn-primary">' . $text . '</button>
          </div>
        </div>';

	return $field;
});






