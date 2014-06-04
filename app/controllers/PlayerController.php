<?php

/**
 * For all player related stuff
 */
class PlayerController extends BaseController {

    public function showProfile($id) {
        $user = User::find($id);

        if (!$user)
            App::abort(404);

        $player = $user->player;

        return View::make("players/player", array('user' => $user, 'player' => $player));
    }

    public function showPlayerSettings() {
        if (!Auth::check())
            return Redirect::route("index");

        $currentUser = Auth::user();
        $currentPlayer = $currentUser->player;

        $team = null;
        if ($currentPlayer->teamID)
            $team = Team::find($currentPlayer->teamID);

        return View::make("players/settings", array('user' => $currentUser, 'player' => $currentPlayer, 'team' => $team));
    }

    public function saveSettingsData() {
        if (!Auth::check())
            return Redirect::route("index");

        $currentUser = Auth::user();
        $currentPlayer = $currentUser->player;

        $currentPlayer->name = Input::get('name');
        $currentPlayer->nick = Input::get('nick');
        $currentPlayer->last_name = Input::get('lastname');
        $currentPlayer->bio = Input::get('about');
        $currentPlayer->position = Input::get('position');
        $currentPlayer->country = Input::get('country');
        $currentPlayer->facebook = Input::get('fbpro');
        $currentPlayer->twitter = Input::get('twpro');
        $currentPlayer->website = Input::get('weblink');

        //DOPRAVI VALIDACIJU ZA SLIKE
        if (Input::hasFile('image')) {
            $file = Input::file('image');

            $destinationPath = public_path() . '/uploads/';
            $filename = str_random(12) . '.' . $file->getClientOriginalExtension();
            $extension = $file->getClientOriginalExtension();

            $currentPlayer->avatar = asset('uploads/' . $filename);

            $file->move($destinationPath, $filename);
        }

        $currentPlayer->save();

        return Redirect::route("playerSettingsView")
                        ->with('global-title', 'Saving complete')
                        ->with('global-text', 'Your profile info has been saved!')
                        ->with('global-class', 'success');
    }

    public function allTeams() {
        $players = Player::playersWithTeams();

        return View::make("players/players", array('players' => $players));
    }

}
