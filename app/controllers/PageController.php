<?php

class PageController extends BaseController
{
    /**
     * GET method, index route (/)
     * @return mixed View
     */
    public function getIndex()
    {
        return Redirect::to('/');
    }

    /**
     * GET method, tortenet route (/tortenet)
     * Displays the history (DB: pages/id=tortenet)
     * @return mixed View
     */
    public function getTortenet() {
        $page = Pages::find('tortenet');

        $options = array(
            'before'  => '<img alt="Kezdetek" src="//i.imgur.com/wRRp9Jt.jpg" style="float: left; border: 1px solid black; margin-right: 5px" />',
            'id'      => 'tortenet',
            'title'   => $page->title,
            'content' => $page->content
        );

        return View::make('layouts.pages.page', $options);
    }

    /**
     * GET method, edzesek route (/edzesek)
     * Displays the order of shootings (DB: pages/id=edzesek)
     * @return mixed View
     */
    public function getEdzesek() {
        $page = Pages::find('edzesek');

        $options = array(
            'id'      => 'edzesek',
            'title'   => $page->title,
            'content' => $page->content
        );

        return View::make('layouts.pages.page', $options);
    }

    /**
     * GET method, arak route (/arak)
     * Displays the prices (DB: pages/id=arak)
     * @return mixed View
     */
    public function getArak() {
        $page = Pages::find('arak');

        $options = array(
            'id'      => 'arak',
            'title'   => $page->title,
            'content' => $page->content
        );

        return View::make('layouts.pages.page', $options);
    }

    /**
     * GET method, elerhetosegek route (/elerhetosegek)
     * Displays the contact details (DB: pages/id=elerhetosegek)
     * @return mixed View
     */
    public function getElerhetosegek() {
        $page = Pages::find('elerhetosegek');

        $options = array(
            'id'      => 'elerhetosegek',
            'title'   => $page->title,
            'content' => $page->content,
            'scripts' => '<script src="//maps.googleapis.com/maps/api/js?v=3&sensor=false" type="text/javascript"></script>
                        <script src="' . secure_asset('js/map.js') .'" type="text/javascript"></script>',
            'after'   => '<div id="map-canvas" class="vertical-space" style="margin-top: 20px; max-width: none;  height: 400px;"><p>A térkép nem jeleníthető meg. Kérlek, frissíts.</p></div>'
        );

        return View::make('layouts.pages.page', $options);
    }

    /**
     * GET method, szerkesztes route (/szerkesztes/$id)
     * Displays the editor form to edit a page with the given id.
     * @param $id       Id of the page to edit.
     * @return mixed    View
     */
    public function getSzerkesztes($id) {
        // Check admin rights
        if (!Session::has('admin')) {
            return Redirect::to('/');
        }

        $page = Pages::find($id);

        // Check if real id
        if (is_null($page)) {
            return Redirect::to('/');
        }

        $options = array(
            'pageTitle'     => 'Oldal szerkesztése',
            'editAction'    => '/rolunk/szerkesztes/' . $id,
            'titleReadonly' => false,
            'title'         => $page->title,
            'content'       => $page->content
        );

        return View::make('layouts.editor.editor', $options);
    }

    /**
     * POST method, szerkesztes route (/szerkesztes/$id)
     * Updates the content of a page with the given id.
     * @param $id       Id of the page to update.
     * @return mixed    View.
     */
    public function postSzerkesztes($id) {
        // Check admin rights
        if (!Session::has('admin')) {
            return Redirect::to('/');
        }

        $page = Pages::find($id);

        // Check if real id
        if (is_null($page)) {
            return Redirect::to('/');
        }

        $page->title = Input::get('title');
        $page->content = Input::get('content');
        $page->save();

        return Redirect::to('/rolunk/' . $id)->with('message',
            array( 'message' => 'Oldal frissítve.', 'type' => 'success'));;
    }
}
