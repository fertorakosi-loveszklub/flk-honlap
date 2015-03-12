<?php

class AlbumController extends BaseController
{
    /**
     * GET method, index route (/)
     * Displays the list of all albums.
     * @return mixed View
     */
    public function getIndex()
    {
        $albums = Album::orderBy('created_at', 'desc')->get();
        return View::make('layouts.albums.list', array('albums' => $albums));
    }

    /**
     * GET method, custom route (//album/$id/$title)
     * Displays all images of an album with the given id.
     * @param $id       Id of the album to display.
     * @param $title    Title of the album. Unused.
     * @return mixed    View
     */
    public function getShowAlbum($id, $title) {
        $album = Album::find($id);

        // Check if album is valid
        if (is_null($album)) {
            return Redirect::to('/albumok/');
        }

        return View::make('layouts.albums.show', array('album' => $album));
    }

    /**
     * POST method, uj route (/uj)
     * Saves a new album.
     * @return mixed View
     */
    public function postUj() {
        // Check admin rights
        if (!Session::has('admin')) {
            return Redirect::to('/');
        }

        if (! Input::has('title') || ! Input::has('albumURL')) {
            Redirect::to('/albumok/')->with('message', array( 'message' => 'Hiányzó cím vagy URL',
                'type' => 'danger'));
        }

        $album = new Album;
        $album->title = Input::get('title');
        $album->albumURL = Input::get('albumURL');
        $album->save();

        return Redirect::to('/album/' . $album->id . '/' . News::urlFriendlify($album->title))->with('message',
            array( 'message' => 'Album létrehozva', 'type' => 'success'));
    }

    /**
     * POST method, szerkesztes route (/szerkesztes/$id)
     * Saves the changes to an album with the given id.
     * @param $id       Id of the album to save.
     * @return mixed    View
     */
    public function postSzerkesztes($id) {
        // Check admin rights
        if (!Session::has('admin')) {
            return Redirect::to('/');
        }

        $album = Album::find($id);

        // Check if album is valid
        if (is_null($album)) {
            return Redirect::to('/albumok/');
        }

        $album->title = Input::get('title');
        $album->albumURL = Input::get('albumURL');
        $album->save();

        return Redirect::to('/album/' . $id . '/' . News::urlFriendlify($album->title))->with('message',
            array( 'message' => 'Album frissítve', 'type' => 'success'));
    }

    /**
     * GET method, torles route (/torles/$id)
     * Deletes an album with the given id.
     * @param $id       ID of the album to delete.
     * @return mixed    View
     */
    public function getTorles($id) {
        // Check admin rights
        if (!Session::has('admin')) {
            return Redirect::to('/');
        }

        $album = Album::find($id);

        // Check if real id
        if (is_null($album)) {
            return Redirect::to('/');
        }

        $album->delete();

        return Redirect::to('/albumok/')->with('message',
            array( 'message' => 'Album törölve.', 'type' => 'success'));
    }
}