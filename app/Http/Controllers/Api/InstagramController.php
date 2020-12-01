<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\API\ComplainFormApi;
use App\Models\API\ThreadApi;

use App\Models\User;
use App\Models\Message;

class InstagramController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = BaseModel::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    private $cf;
    private $thread;
    private $user;
    private $message;
    private $GraphURL;

    public function __construct(ComplainFormApi $cf,ThreadApi $thread,User $user,Message $message)
    {
         $this->cf = $cf;
         $this->thread = $thread;
         $this->user = $user;
         $this->message = $message;
         $this->GraphURL = "https://graph.facebook.com/v9.0/";
    }

    public function index(Request $request){

        $datas = [];

        $cursor = $request->input('cursor');
        $cursorType = $request->input('cursor_type');

        // get media
        $user =  $this->user->find(auth()->user()->user_id);

        if(is_object($user)){

            if(trim($user->fb_token) != ''
                && trim($user->ig_page_id) != ''){

                    $datas = $this->getAPage($user->ig_page_id,$user->fb_token,10,$cursorType,$cursor);

                }

        }

        return response()->json($datas);

    }

    public function singleMedia($id, Request $request){

        $datas = [
            'metadata' => [],
            'insights' => []
        ];

        // get media
        $user =  $this->user->find(auth()->user()->user_id);

        if(is_object($user)){

            if(trim($user->fb_token) != ''
                && trim($user->ig_page_id) != ''){

                    $usersMediaEndpoint = $this->GraphURL . $id;
		
                    // endpoint params required
                    $usersMediaParams = array( 
                        'fields' => 'caption,comments_count,media_url,media_type,timestamp',
                        'access_token' => $user->fb_token
                    );
            
                    // make the api call
                    $datas['metadata'] = $this->makeApiCall( $usersMediaEndpoint, 'GET', $usersMediaParams );

                    $usersMediaEndpoint = $this->GraphURL . $id . '/insights';
		
                    // endpoint params required
                    $usersMediaParams = array( 
                        'metric' => 'impressions,engagement,reach',
                        'access_token' => $user->fb_token
                    );
            
                    // make the api call
                    $datas['insights'] = $this->makeApiCall( $usersMediaEndpoint, 'GET', $usersMediaParams );

                }

        }

        return response()->json($datas);
    }

    public function mediaComment($id, Request $request){

        $datas = [];

        // get media
        $user =  $this->user->find(auth()->user()->user_id);

        if(is_object($user)){

            if(trim($user->fb_token) != ''
                && trim($user->ig_page_id) != ''){

                    $usersMediaEndpoint = $this->GraphURL . $id . '/comments';
		
                    // endpoint params required
                    $usersMediaParams = array( 
                        'fields' => 'username,text,timestamp,replies{username,text,timestamp}',
                        'access_token' => $user->fb_token
                    );
            
                    // make the api call
                    $datas = $this->makeApiCall( $usersMediaEndpoint, 'GET', $usersMediaParams );

                }

        }

        return response()->json($datas);
    }

    public function getReplies($id, Request $request){

        $datas = [];

        // get media
        $user =  $this->user->find(auth()->user()->user_id);

        if(is_object($user)){

            if(trim($user->fb_token) != ''
                && trim($user->ig_page_id) != ''){

                    $usersMediaEndpoint = $this->GraphURL . $id . '/replies';
		
                    // endpoint params required
                    $usersMediaParams = array( 
                        'fields' => 'username,text,timestamp',
                        'access_token' => $user->fb_token
                    );
            
                    // make the api call
                    $datas = $this->makeApiCall( $usersMediaEndpoint, 'GET', $usersMediaParams );

                }

        }

        return response()->json($datas);
    }

    public function storeReply($id, Request $request){

        $datas = [];

        // get media
        $user =  $this->user->find(auth()->user()->user_id);
        $message = $request->input('reply');

        if(is_object($user)){

            if(trim($user->fb_token) != ''
                && trim($user->ig_page_id) != ''
                    && trim($message) != ''){

                    $usersMediaEndpoint = $this->GraphURL . $id . '/replies';

                    // endpoint params required
                    $usersMediaParams = array( 
                        'message' => $message,
                        'access_token' => $user->fb_token
                    );
            
                    // make the api call
                    $datas = $this->makeApiCall( $usersMediaEndpoint, 'POST', $usersMediaParams );

                }

        }

        return response()->json($datas);
    }

	public function makeApiCall( $endpoint, $type, $params ) {
		// initialize curl
        $ch = curl_init();

		// combine endpoint and params and set other curl options
		if ( 'POST' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		} elseif ( 'GET' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
        }

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        
		// get response
		$response = curl_exec( $ch );

		// close curl
		curl_close( $ch );

		// json decode and return response
		return json_decode( $response, true );
    }    
 
	public function getAPage( $instagramAccountId, $accessToken, $limit, $cursorType, $cursor ) {
		// endpoint structure for getting users media -> https://graph.facebook.com/v5.0/{ig-account-id}/media
        
		$usersMediaEndpoint = $this->GraphURL . $instagramAccountId . '/media';
		
		// endpoint params required
		$usersMediaParams = array( 
			'fields' => 'id,caption,comments_count,media_type,media_url,permalink,thumbnail_url,timestamp,username',
			'limit' => $limit,
			'access_token' => $accessToken
		);

		if ( $cursorType && $cursor ) { // if cursor and cursor type exists the add them onto the params
			$usersMediaParams[$cursorType] = $cursor;
		}

		// make the api call
		$usersMedia = $this->makeApiCall( $usersMediaEndpoint, 'GET', $usersMediaParams );

		// return the response
		return $usersMedia;
	}
}
