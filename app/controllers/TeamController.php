<?php

class TeamController extends BaseController {

    // Display list of teams
    public function allTeams() {


        $teams = Team::paginate(15);

        return View::make("teams/teams", array('teams' => $teams));
    }

    public function teamProfile($id) {

        $team = Team::find($id);

        $currentUser = Auth::user();
        $currentPlayer = $currentUser->player;

        $captain = false;
        if ($currentPlayer->playerID == $team->captain)
            $captain = true;

        return View::make("teams/team", array('user' => $currentUser, 'player' => $currentPlayer, 'team' => $team, 'captain' => $captain));
    }

    /**
     * ZA BRISANJE
     * zamenjeno sa teamProfile i createView (vidi nav.blade.php) linije 28-32
     */
    /*public function myTeam() {

        $currentUser = Auth::user();
        $currentPlayer = $currentUser->player;

        $team = null;
        $captain = false;
        if ($currentPlayer->teamID) {
            $team = Team::find($currentPlayer->teamID);

            if ($currentPlayer->playerID == $team->captain)
                $captain = true;
        }

        return View::make("teams/myteam", array('user' => $currentUser, 'player' => $currentPlayer, 'team' => $team, 'captain' => $captain));
    }*/

    public function createData() {

        $validator = Validator::make(Input::all(), array(
                    'teamname' => "required|max:80|min:3|unique:teams,name",
                    'teamtag' => "required|max:20|min:3",
                    'image' => "image|mimes:jpeg,bmp,png|max:512",
                    'twitter' => "url",
                    'facebook' => "url",
                    'website' => "url"
                        )
        );

        if ($validator->fails()) {
            return Redirect::route('createNewTeam')
                            // Send errors
                            ->withErrors($validator)
                            // Send old inputs
                            ->withInput();
        } else {
            $currentUser = Auth::user();
            $currentPlayer = $currentUser->player;

            $filename = null;
            if (Input::hasFile('image')) {
                $file = Input::file('image');

                $destinationPath = public_path() . '/uploads/';
                $filename = str_random(12) . '.' . $file->getClientOriginalExtension();
                $extension = $file->getClientOriginalExtension();

                $file->move($destinationPath, $filename);
            }


            $newTeam = Team::create(array(
                        'name' => Input::get('teamname'),
                        'tag' => Input::get('teamtag'),
                        'captain' => $currentPlayer->playerID,
                        'facebook' => Input::get('facebook'),
                        'twitter' => Input::get('twitter'),
                        'website' => Input::get('website'),
                        'about' => Input::get('about'),
                        'avatar' => $filename,
                        'country' => Input::get('country')
            ));

            if ($newTeam) {
                $currentPlayer->teamID = $newTeam->teamID;
                $currentPlayer->save();

                return Redirect::route("my-team")
                                ->with('global-title', 'Your Team is created')
                                ->with('global-text', 'You can now invite some players to join your team.')
                                ->with('global-class', 'success');
            } else {
                return Redirect::route("createNewTeam")
                                ->with('global-title', 'Team couldn\'t be created.')
                                ->with('global-text', 'Internal error, sorry for the inconvenience. Please contact support.')
                                ->with('global-class', 'error');
            }
        }
    }

    public function createView() {
        
        $currentUser = Auth::user();
        $currentPlayer = $currentUser->player;
        
         if ($currentPlayer->teamID) {
             return Redirect::route("team", $currentPlayer->teamID)
                        ->with('global-title', 'You have team already!')
                        ->with('global-text', 'You are in a team at the moment. You can not be in two teams in same time. Please leave your team, and try again!')
                        ->with('global-class', 'success');
         }
        else{
           return View::make("teams/create");
        }
    }
    
    public function editView($id){
        
        $team = Team::find($id);
        $player = Auth::user()->player;
        $teamPlayers = Player::where('teamID', '=', $id)->get();
        
        if ( $player->teamID == $id && $team->captain == $player->playerID )
        {
            return View::make("teams/edit", array( "team" => $team, "captain" => $player, "teamPlayers" => $teamPlayers));
        }
        else{
            return Redirect::route("index")
                        ->with('global-title', 'Error!')
                        ->with('global-text', 'You have no permission to edit this team! If you belive this is an error, please contact support!')
                        ->with('global-class', 'danger');
        
         }
    }

    public function editData($id) {
        $team = Team::find($id);
        $player = Auth::user()->player;

        if ( $player->teamID == $id && $team->captain == $player->playerID )
        {
            $validator = Validator::make(Input::all(), array(
                        'teamname' => "required|max:80|min:3|unique:teams,name," . $team->name . ",name",
                        'teamtag' => "required|max:20|min:3",
                        'image' => "image|mimes:jpeg,bmp,png|max:512",
                        'twitter' => "url",
                        'facebook' => "url",
                        'website' => "url"
                            )
            );

            if ($validator->fails()) {
                return Redirect::route('editTeamView', $id)
                                // Send errors
                                ->withErrors($validator)
                                // Send old inputs
                                ->withInput();
            } else {
                $currentUser = Auth::user();
                $currentPlayer = $currentUser->player;

                //$filename = null;
                if (Input::hasFile('image')) {
                    $file = Input::file('image');

                    $destinationPath = public_path() . '/uploads/';
                    $filename = str_random(12) . '.' . $file->getClientOriginalExtension();
                    $extension = $file->getClientOriginalExtension();

                    $file->move($destinationPath, $filename);

                    $team->avatar = $filename;
                }


                $team->name = Input::get('teamname');
                $team->tag = Input::get('teamtag');
                //$team->captain = $currentPlayer->playerID,
                $team->facebook = Input::get('facebook');
                $team->twitter = Input::get('twitter');
                $team->website = Input::get('website');
                $team->about = Input::get('about');
                $team->country = Input::get('country');


                if ($team->save()) {
                    //$currentPlayer->teamID = $newTeam->teamID;
                    //$currentPlayer->save();

                    return Redirect::route('editTeamView', $id)
                                    ->with('global-title', 'Success')
                                    ->with('global-text', 'Team changes are saved.')
                                    ->with('global-class', 'success');
                } else {
                    return Redirect::route('editTeamView', $id)
                                    ->with('global-title', 'Team settings couldn\'t be saved.')
                                    ->with('global-text', 'Internal error, sorry for the inconvenience. Please contact support.')
                                    ->with('global-class', 'error');
                }
            }            
        }
        else{
            return Redirect::route("index")
                        ->with('global-title', 'Error!')
                        ->with('global-text', 'You have no permission to edit this team! If you belive this is an error, please contact support!')
                        ->with('global-class', 'danger');
        
        }        
    }

    public function removePlayer($id) {
        $playerId = Input::get("playerForRemove");

        $player = Player::find($playerId);

        //proveri da nije mrckao po inputi
        if ($player->teamID != $id) {
            return Redirect::route('index')
                            ->with('global-title', 'Error')
                            ->with('global-text', 'That player is not in your team!')
                            ->with('global-class', 'error');
        } else {
            $player->teamID = null;
            $player->save();
                    return Redirect::route('editTeamView', $id)
                                    ->with('global-title', 'Success')
                                    ->with('global-text', 'Player is not in your team anymore')
                                    ->with('global-class', 'success');
        }
    }

}
