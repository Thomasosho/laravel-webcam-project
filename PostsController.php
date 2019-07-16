<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use SMSNotification;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Post;
use App\User;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{

    use Notifiable;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //return Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title','desc')->take(1)->get();
        //$posts = Post::orderBy('title','desc')->get();
        $posts = Post::where('date','desc')->paginate(5);
        $posts = DB::table('posts')->paginate(5);
        return view('posts.index')->with('posts', $posts);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $posts = DB::table('posts')->where('visitor_name', 'like', '%'.$search.'%')->paginate(5);
        return view('posts/index', ['posts' => $posts]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'visitor_name' => 'required',
            'visitor_phone' => 'required',
            'date' => 'required',
        ]);
        

        // Create Post
        $post = new Post;
        $post->visitor_name = $request->input('visitor_name');
        $post->visitor_phone = $request->input('visitor_phone');
        $post->date = $request->input('date');
        $post->notificationTime = $request->input('notificationTime');
        $post->timezoneOffset = $request->input('timezoneOffset');
        $post->delta = $request->input('delta');

        $notificationTime = Carbon::parse($request->input('date'))->subMinutes($request->delta);
        $post->notificationTime = $notificationTime;
        
        $post->user_id = auth()->user()->id;
        $post->save();
        $this->sendMessage('You Have been Scheduled for an appointment @Solid Minerals Development Fund. Please come with this message to show them at the gate!', $post->visitor_phone);
        return redirect('/posts')->with('success', 'Visitor Created, Visitor Alerted!, reminder set');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        // Check for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/')->with('error', 'Unauthorized Page');
        }
        return view('posts.edit')->with('post', $post);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'visitor_name' => 'required',
            'visitor_phone' => 'required'
        ]);
         // Handle File Upload
         if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }
        // Update Post
        $post = Post::find($id);
        $post->visitor_name = $request->input('visitor_name');
        $post->visitor_phone = $request->input('visitor_phone');
        $post->coy_name = $request->input('coy_name');
        $post->coy_address = $request->input('coy_address');
        $post->date = $request->input('date');
        $post->status = $request->input('status');
        $post->time_in = $request->input('time_in');
        $post->time_out = $request->input('time_out');
        
        $post->delta = $request->input('delta');
        $post->timezoneOffset = $request->input('timezoneOffset');

        $notificationTime = Carbon::parse($request->input('date'))->subMinutes($request->delta);
        $post->notificationTime = $notificationTime;

        if($request->hasFile('cover_image')){
        $post->cover_image = $fileNameToStore;
        }
        $post->save();
        return redirect('/')->with('success', 'Visitor Updated');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        // Check for correct user
        if($post->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->delete();
        return redirect('/')->with('success', 'Visitor Removed');
    }

    private function postFromRequest(Request $request) {
        $this->validate($request, $this->validInputConditions);
        $post = new \App\Post;

        $post->visitor_name = $request->input('visitor_name');
        $post->visitor_phone = $request->input('visitor_phone');
        $post->timezoneOffset = $request->input('timezoneOffset');
        $post->date = $request->input('date');

        $notificationTime = Carbon::parse($request->input('date'))->subMinutes($request->delta);
        $post->notificationTime = $notificationTime;

        return $post;
    }

    private $SMS_SENDER = "SMDF";
    private $RESPONSE_TYPE = 'json';
    private $SMS_USERNAME = 'tomasosho';
    private $SMS_PASSWORD = 'samoht';


    public function getUserNumber(Request $request)
    {
        $visitor_phone = $request->input('visitor_phone');

        $message = "You Have Been Scheduled for Meeting. Come with this message on the {{$post->date}}.";

        $this->initiateSmsActivation($visitor_phone, $message);
//        $this->initiateSmsGuzzle($visitor_phone, $message);

        return redirect()->back()->with('message', 'Message has been sent successfully');
    }


    /* public function initiateSmsActivation($visitor_phone, $message){
        $isError = 0;
        $errorMessage = true;

        //Preparing post parameters
        $postData = array(
            'username' => $this->SMS_USERNAME,
            'password' => $this->SMS_PASSWORD,
            'message' => $message,
            'sender' => $this->SMS_SENDER,
            'mobiles' => $post->visitor_phone,
            'response' => $this->RESPONSE_TYPE
        );

        $url = "http://portal.bulksmsnigeria.net/api/";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);


        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);


        if($isError){
            return array('error' => 1 , 'message' => $errorMessage);
        }else{
            return array('error' => 0 );
        }
    }

      /**
     * Send message to a selected users
     */
    public function sendCustomMessage(Request $request)
    {
        $validatedData = $request->validate([
            'posts' => 'required|array',
            'body' => 'required',
        ]);
        $recipients = $validatedData["posts"];
        // iterate over the array of recipients and send a twilio request for each
        foreach ($recipients as $recipient) {
            $this->sendMessage($validatedData["body"], $recipient);
        }
        return back()->with(['success' => "Messages on their way!"]);
    }
    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients Number of recipient
     */
    private function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
    }

}