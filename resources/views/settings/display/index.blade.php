@extends('settings.index')

@section('setting-content')
<div class="panel panel-default">
    <div class="panel-heading">表示</div>
    <div class="panel-body">
        <form role="form" method="POST" action="/settings/display/update">
            <div class="form-group">
                <label class="control-label">表示形式</label>
                <select class="form-control" name="display_format">
                    <option value="0"{{ !$setting->display_format ? ' selected' : '' }}>リスト</option>
                    <option value="1"{{ $setting->display_format === 1 ? ' selected' : '' }}>アルバム</option>
                </select>
            </div>
            <div class="form-group cp_ipcheck">
                <input type="checkbox" name="animation" id="c_ch2"{{ $setting->animation ? ' checked' : '' }}>
                <label for="c_ch2">アニメーションの有効化</label>
            </div>
            {{ csrf_field() }}
            <button type="submit" class="btn btn-block btn-primary">更新</button>
        </form>
    </div>
</div>
@endsection
