@extends('layouts.master')

@section('content')
    <div class="content text-justify">
        <h2><i class="fa fa-plus"></i> Új eredmény feltöltése</h2>

        {{ Form::open(array('url' => URL::to('rekordok/uj', array(), true), 'class' => 'form-horizontal')) }}
        <div class="form-group">
            <label for="category" class="col-sm-3 control-label">Kép</label>
            <div class="col-sm-9">
                <input id="imageupload" accept="image/jpg, image/jpeg" type="file" name="files[]">
                <div class="progress" id="progress">
                    <div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0%;">
                        <span><span id="percentage">45</span>%</span>
                    </div>
                </div>
                <img id="preview" alt="Előnézet betöltése..."/>
            </div>
            <input type="hidden" id="imgurl" name="imgurl" value="">
        </div>

        <div class="alert alert-danger" role="alert" id="error">
            Hiba történt a feltöltés közben. Kérlek ellenőrizd, valódi jpeg képet szeretnél-e feltölteni.
        </div>
        <hr />
        <div class="row" id="imageprompt">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-9">
                <p>Első lépésben töltsd fel a képet. Az adatokat utána tudod kitölteni.</p>
            </div>
        </div>
        <div class="form-group">
            <label for="category" class="col-sm-3 control-label">Kategória</label>
            <div class="col-sm-9">
                <select name="category" id="category" class="form-control default-disabled">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="points" class="col-sm-3 control-label">Köregység</label>
            <div class="col-sm-9">
                <input type="text" class="default-disabled form-control" id="points" name="points" placeholder="90" required>
            </div>
        </div>
        <div class="form-group">
            <label for="shots" class="col-sm-3 control-label">Lövések száma</label>
            <div class="col-sm-9">
                <input type="text" class="default-disabled form-control" id="shots" name="shots" placeholder="10" required>
            </div>
        </div>
        <div class="form-group">
            <label for="shot_at" class="col-sm-3 control-label">Dátum</label>
            <div class="col-sm-9">
                <input type="text" class="default-disabled form-control" id="shot_at" name="shot_at" placeholder="2015-01-01" required>
            </div>
        </div>

        <div class="form-group">
            <label for="visibility" class="col-sm-3 control-label">Láthatóság</label>
            <div class="col-sm-9">
                <div class="radio">
                    <label>
                        <input class="default-disabled" type="radio" name="visibility" id="private" value="private">
                        Privát - csak én látom, nem jelenik meg az egyéni rekordok között
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input class="default-disabled" type="radio" name="visibility" id="public" value="public" checked>
                        Nyilvános - megjelenik az egyéni rekordok között
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label"></div>
            <div class="col-sm-9 text-right">
                <input type="submit" class="default-disabled btn btn-primary" value="Mentés">
            </div>
        </div>

        {{ Form::close() }}
    </div>
@overwrite

@section('scripts')
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="{{ secure_asset('js/jqfileupload/jquery.ui.widget.js') }}"></script>
    <script src="{{ secure_asset('js/jqfileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ secure_asset('js/imgurthumbnail.js') }}"></script>
    <script src="{{ secure_asset('js/imgupload.js') }}"></script>
@overwrite
