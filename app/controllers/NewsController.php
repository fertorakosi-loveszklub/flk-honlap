<?php

    class NewsController extends BaseController
    {
        /**
         * GET method, index route (/)
         * Displays the list of the last 10 news.
         * @return mixed View
         */
        public function getIndex()
        {
            $news = News::with('author')->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

            return View::make('layouts.news.list', array('news' => $news));
        }

        /**
         * GET method, custom route (//hir/$id/$title)
         * Shows a news with the given id.
         * @param $id       Id of the news to show
         * @param $title    Title of the news (unused)
         * @return mixed    View
         */
        public function getShowNews($id, $title) {

            // Check if ID is numeric
            $id = strval($id);

            $news = News::find($id);

            // Check if it exists
            if (is_null($news)) {
                return Redirect::to('/');
            }

            // Display
            return View::make('layouts.news.show', array('news' => $news));
        }

        /**
         * GET method, uj route (/uj)
         * Displays a form for adding a new news
         * @return mixed View
         */
        public function getUj() {
            // Check admin rights
            if (!Session::has('admin')) {
                return Redirect::to('/');
            }

            $options = array(
                'pageTitle'     => 'Új hír írása',
                'editAction'    => '/hirek/uj',
                'titleReadonly' => false,
                'title'         => '',
                'content'       => '',

            );
            return View::make('layouts.editor.editor', $options);
        }

        /**
         * POST method, uj route (/uj)
         * Saves a new news.
         * @return mixed View
         */
        public function postUj() {
            // Check admin rights
            if (!Session::has('admin')) {
                return Redirect::to('/');
            }

            if (!Input::has('title') || !Input::has('content')) {
                Redirect::to('/hirek/uj')->with('message', array( 'message' => 'Hiányzó cím vagy szöveg',
                    'type' => 'danger'));
            }

            $n = new News;
            $n->title = htmlentities(Input::get('title'));
            $n->content = Input::get('content');
            $n->user_id = Auth::user()->id;

            $n->save();

            return Redirect::to('hirek')->with('message', array( 'message' => 'Hír létrehozva.',
                'type' => 'success'));
        }

        /**
         * GET method, szerkesztes route (/szerkesztes/$id)
         * Displays a form for editing news.
         * @param $id       News to edit.
         * @return mixed    View.
         */
        public function getSzerkesztes($id) {
            // Check admin rights
            if (!Session::has('admin')) {
                return Redirect::to('/');
            }

            $hir = News::find($id);

            // Check if real id
            if (is_null($hir)) {
                return Redirect::to('/');
            }

            $options = array(
                'pageTitle'     => 'Hír szerkesztése',
                'editAction'    => '/hirek/szerkesztes/' . $hir->id,
                'titleReadonly' => false,
                'title'         => $hir->title,
                'content'       => $hir->content
            );

            return View::make('layouts.editor.editor', $options);
        }

        /**
         * POST method, szerkesztes route (/szerkesztes/$id)
         * Updates an existing news with the given id.
         * @param $id       Id of the news to update.
         * @return mixed    View
         */
        public function postSzerkesztes($id) {
            // Check admin rights
            if (!Session::has('admin')) {
                return Redirect::to('/');
            }

            $hir = News::find($id);

            // Check if real id
            if (is_null($hir)) {
                return Redirect::to('/');
            }

            $hir->title = Input::get('title');
            $hir->content = Input::get('content');
            $hir->save();

            return Redirect::to('/hir/' . $id . '/' . News::urlFriendlify($hir->title))->with('message',
                array( 'message' => 'Hír frissítve.', 'type' => 'success'));
        }

        /**
         * GET method, torles route (/torles/$id)
         * Deletes a news with the given id.
         * @param $id       Id of the news to delete.
         * @return mixed    View
         */
        public function getTorles($id) {
            // Check admin rights
            if (!Session::has('admin')) {
                return Redirect::to('/');
            }

            $hir = News::find($id);

            // Check if real id
            if (is_null($hir)) {
                return Redirect::to('/');
            }

            $hir->delete();

            return Redirect::to('/hirek/')->with('message',
                array( 'message' => 'Hír törölve.', 'type' => 'success'));
        }
    }
