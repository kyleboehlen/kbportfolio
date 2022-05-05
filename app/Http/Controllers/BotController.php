<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

// Models
use App\Models\Discord\Bots;

// Requests
use App\Http\Requests\Bots\StoreRequest;
use App\Http\Requests\Bots\UpdateRequest;

class BotController extends Controller
{
    /**
     * Instantiate a new BotController instance.
     */
    public function __construct()
    {
        $this->action_nav_opts = [
            [
                'text' => 'Add Bot',
                'route' => 'admin.bots.add',
                'icon' => 'add',
            ]
        ];

        foreach (Bots::all() as $bot) {
            array_push($this->action_nav_opts, [
                'text' => $bot->name,
                'route' => 'admin.bots.edit',
                'params' => [
                    'bot' => $bot->id,
                ],
                'icon' => 'bot',
            ]);
        }
    }

    /**
     * The admin form to create a new discord bot
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add()
    {
        return view('admin.bots.form')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Add Bot',
        ]);
    }

    /**
     * Stores a new discord bot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        // Create new bot
        $bot = new Bots([
            'name' => $request->get('name'),
            'desc' => $request->get('desc'),
            'client_id' => $request->get('client-id'),
        ]);

        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Encode scope(s)
            $bot->scope = str_replace(' ', '%20', trim($request->get('scope')));

            // Check for permissions
            if($request->has('permissions'))
            {
                $bot->permissions = $request->get('permissions');
            }

            // Save the bot
            if(!$bot->save())
            {
                // Log errors
                Log::error('Failed to save new bot', [
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to save a new discord bot, see logs',
                ]);
            }

            // Upload image
            $bot->img = str_replace(config('filesystems.dir.discord') . '/', '', Storage::put(config('filesystems.dir.discord'), $request->file('img')));

            if(!$bot->save())
            {
                // Log errors
                Log::error('Failed to associate img with discord bot', [
                    'bot->id' => $bot->id,
                    'bot->img' => $bot->img,
                ]);
            }
        }

        return redirect()->route('admin.bots.add')->with([
            'success_alert' => "Created new discord bot $bot->name",
        ]);
    }

    /**
     * Returns the info on the discord bot for update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Bots $bot)
    {
        return view('admin.bots.form')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Edit Bot',
            'bot' => $bot,
        ]);
    }

    /**
     * Updates the properities and img of a discord bot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Bots $bot)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            $bot->name = $request->get('name');
            $bot->desc = $request->get('desc');
            $bot->client_id = $request->get('client-id');
            $bot->permissions = $request->get('permissions');

            // Encode scope(s)
            $bot->scope = str_replace(' ', '%20', trim($request->get('scope')));

            if($request->has('img'))
            {
                // Upload img
                $bot->img = $request->file('img')->store('public/images/discord');
                $bot->img = str_replace('public/images/discord/', '', $bot->img);
            }

            // Save the bot
            if(!$bot->save())
            {
                // Log errors
                Log::error('Failed to update software bot', [
                    'bot' => $bot->toArray(),
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to update the software bot, see logs',
                ]);
            }
        }

        return redirect()->route('admin.bots.edit', ['bot' => $bot->id])->with([
            'success_alert' => "Updated software bot $bot->name",
        ]);
    }

    /**
     * Soft deletes a discord bot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bots $bot)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Attempt to soft delete bot
            if(!$bot->delete())
            {
                // Log errors
                Log::error('Failed to delete discord bot', [
                    'bot' => $bot->toArray(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to delete discord bot, see logs',
                ]);
            }
        }

        return redirect()->route('admin.bots.add')->with([
            'success_alert' => "Successfully deleted Discord bot $bot->name",
        ]);
    }

    /**
     * Creates a json response of the bots and their properties
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        // Get all bots
        $bots = Bots::all();

        // Set response properties
        $response = array();
        $response['success'] = true;
        $response['num_bots'] = $bots->count();

        // Add all the bots to the reponse
        $bots_array = array();
        $properties = ['name', 'desc', 'img', ];
        foreach ($bots as $bot) {
            foreach ($properties as $prop) {
                $bots_array[$prop] = $bot->$prop;
            }

            $bots_array['invite_url'] = $bot->getInviteUrl();
        }

        $response['bots'] = $bots_array;

        return response()->json($response);
    }
}
