<?php

class RecordController extends BaseController
{
    /**
     * GET method, index route (/)
     * Displays the overview of the top records.
     * @return mixed View
     */
    public function getIndex()
    {
        // Get categories
        $categories = RecordCategory::all();

        return View::make('layouts.records.list', array('categories' => $categories));
    }

    /**
     * GET method, uj route(/uj
     * Displays the form to upload a new record.
     * @return mixed View
     */
    public function getUj()
    {
        // Get if user is a member
        if (!Session::has('member')) {
            return Redirect::to('/rekordok');
        }

        $categories = RecordCategory::all();

        return View::make('layouts.records.new', array('categories' => $categories));
    }

    /**
     * POST method, uj route (/uj)
     * Saves a new record.
     * @return mixed View
     */
    public function postUj()
    {
        // Check if user is a member
        if (!Session::has('member')) {
            return Redirect::to('/rekordok');
        }

        // Validate input
        $validator = Validator::make(
            Input::all(),
            array(
                'imgurl'        => 'regex:/^https?:\/\/i\.imgur\.com\/[a-zA-Z0-9]+\.jpe?g$/',
                'category'      => 'exists:record_categories,id',
                'shots'         => 'integer|between:1,30',
                'points'        => 'integer|between:1,300',
                'shot_at'       => 'date',
                'visibility'    => 'in:private,public'
            ));

        if ($validator->fails()) {
            return Redirect::to('/rekordok/uj')->with('message',
                array( 'message' => 'Hibás adatok.', 'type' => 'danger'));
        }

        $is_public = Input::get('visibility') == 'public' ? true : false;

        // Save record
        $record = new Record;
        $record->user_id        = Auth::user()->id;
        $record->category_id    = Input::get('category');
        $record->shots          = Input::get('shots');
        $record->points         = Input::get('points');
        $record->shot_at        = Input::get('shot_at');
        $record->image_url      = Input::get('imgurl');
        $record->shots_average  = round($record->points / $record->shots * 10, 2);
        $record->is_public      = $is_public;
        $record->save();

        return Redirect::to('/rekordok/sajat')->with('message',
            array( 'message' => 'Rekord feltöltve.', 'type' => 'success'));
    }

    /**
     * GET method, sajat route (/sajat)
     * Displays the list of all own records.
     * @return mixed View
     */
    public function getSajat() {
        // Check if user is a member
        if (!Session::has('member')) {
            return Redirect::to('/rekordok');
        }

        // Get categories
        $categories = RecordCategory::with(array('records' => function($query)
        {
            $query->where('user_id', '=', Auth::user()->id);

        }))->get();

        return View::make('layouts.records.own', array('categories' => $categories));
    }

    /**
     * GET method, rekordok route (/rekordok/$id)
     * Loads top records with given category id.
     * @param $id       Id of the category to get the records of.
     * @return mixed    JSON
     */
    public function getRekordok($id) {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'data'      => null
        );

        // Check if category id is valid
        if(RecordCategory::find($id) == null) {
            $response['message'] = "Érvénytelen kategória";
            return Response::json($response);
        }

        $data = DB::table('records')
            ->join('users', 'records.user_id', '=', 'users.id')
            ->select(DB::raw('users.real_name, records.shot_at, records.shots, records.points,
                            max(records.shots_average) as record, records.category_id, records.image_url'))
            ->where('records.category_id', '=', $id)
            ->where('records.is_public', '=', true)
            ->groupBy('records.user_id')
            ->orderBy('record', 'desc')
            ->get();

        $response['success'] = true;
        $response['data'] = (array)$data;

        return Response::json($response);
    }

    /**
     * GET method, torles route (/torles/$id)
     * Deletes a record with the given id.
     * @param $id       Id of the record to delete.
     * @return mixed    View
     */
    public function getTorles($id) {
        // Check if user is a member
        if (!Session::has('member')) {
            return Redirect::to('/rekordok');
        }

        // Check if record exists and belongs to the user currently logged in
        $record = Record::find($id);

        if ($record == null || $record->user_id != Auth::user()->id) {
            return Redirect::to('/rekordok');
        }

        // Delete record
        $record->delete();

        return Redirect::to('/rekordok/sajat')->with('message',
            array( 'message' => 'Rekord törölve.', 'type' => 'success'));
    }

    /**
     * GET method, lathatosag route (/lathatosag/$id)
     * Toggles the visibility (is_public property) of a given record.
     * @param $id       Id of the record to toggle.
     * @return mixed    JSON
     */
    public function getLathatosag($id) {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'isPublic'  => null
        );

        // Check if user is a member
        if (!Session::has('member')) {
            $response['message'] = 'Hozzáférés megtagadva.';
            return Response::json($response);
        }

        // Check if record exists and belongs to the user currently logged in
        $record = Record::find($id);

        if ($record == null || $record->user_id != Auth::user()->id) {
            $response['message'] = 'Érvénytelen ID vagy nem saját rekord.';
            return Response::json($response);
        }

        $record->is_public = !$record->is_public;
        $record->save();

        $response['success'] = true;
        $response['isPublic'] = $record->is_public;
        return Response::json($response);
    }

    /**
     * GET method, grafikon route (/grafikon/$id)
     * Returns data for displaying a line chart of the progress of the user (records with given category id)
     * @return JSON
     */
    public function getGrafikon($id) {
        $response = array(
            'success'   => 'false',
            'message'   => null,
            'data'  => null
        );

        // Check if user is a member
        if (!Session::has('member')) {
            $response['message'] = 'Hozzáférés megtagadva.';
            return Response::json($response);
        }

        // Get entries for each category
        $entries = DB::table('records')
            ->select(DB::raw('shot_at as date, max(shots_average) as record'))
            ->where('user_id', '=', Auth::user()->id)
            ->where('category_id', '=', $id)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        if (count($entries) == 0){
            $response['message'] = 'Nincsenek eredmények.';
            return Response::json($response);
        }

        /**
         * $entries has one record per per day.
         * We have to make an array which is:
         *  -  An array of arrays, which are
         *      - Data titles
         *      - Data itself
         *
         * Example:
         * [
         *  ['Dátum',       'Eredmény'],
         *  ['2014-01-01',  '80'],
         *  ['2014-01-08',  null],
         *  ['2014-02-01',  '10']
         * ]
         */

        // Create return value
        $data = [];

        // Add first row with headers
        $data[]  = ['Dátum', 'Eredmény (10-es átlag)'];

        foreach ($entries as $entry) {
            $data[] = [$entry->date, $entry->record];
        }

        $response['success'] = true;
        $response['data'] = $data;
        return Response::json($response);
    }
}