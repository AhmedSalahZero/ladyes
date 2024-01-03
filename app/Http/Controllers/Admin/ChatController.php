<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ChatController extends Controller
{
    public function index($id = 0,$id2 = 0){
        $users = ChMessage::groupBy('from_id','to_id')->orderBy('id','DESC')->get();
        $fromUserId = $id; // ID of the "from" user
        $toUserId = $id2; // ID of the "to" user

        $messages = ChMessage::where(function ($query) use ($fromUserId, $toUserId) {
            $query->where('from_id', $fromUserId)->where('to_id', $toUserId);
        })->orWhere(function ($query) use ($fromUserId, $toUserId) {
            $query->where('from_id', $toUserId)->where('to_id', $fromUserId);
        })->take(50)->orderBy('created_at','DESC')->get();

        return view('admin.chat.index',compact('users','messages','id','id2'));
    }


    public function getUsersList(Request $request)
    {

        $users = ChMessage::groupBy('from_id','to_id')->orderBy('id','DESC')->get();
        $user_html = '';
        if (count($users) > 0){
            $project_id = $users[0]->project_id;
        }
        foreach ($users as $user){
            $image = isset($user->from_user->image) ? asset('public'.$user->from_user->image) : '';
            $from_user_name = $user->from_user->name ?? '-';
            $to_user_name = $user->to_user->name ?? '-';
            $name = $from_user_name.' & '.$to_user_name;

            $active_class = ($user->from_id == $request->from_id) && ($user->to_id == $request->to_id) ? 'active_user' : '';
            $user_html .='<a href="'.route('admin.chat.index',['id'=>$user->from_id,'id2'=>$user->to_id]).'" class="media border-0 '.$active_class.'">
                                <div class="media-left pr-1">
                  <span class="avatar avatar-md avatar-online">
                    <img class="media-object rounded-circle"
                         src="'.$image.'"
                         alt="Generic placeholder image">
                    <i></i>
                  </span>
                                </div>
                                <div class="media-body w-100">
                                    <h6 class="list-group-item-heading">'.$name.'</h6>
                                </div>
                            </a>';

        }
        return response()->json([
            'status' => true,
            'user_html' => $user_html
        ]);
    }

    public function getUserMessages($id,$id2){
        $fromUserId = $id; // ID of the "from" user
        $toUserId = $id2; // ID of the "to" user

        $messages = ChMessage::where(function ($query) use ($fromUserId, $toUserId) {
            $query->where('from_id', $fromUserId)->where('to_id', $toUserId);
        })->orWhere(function ($query) use ($fromUserId, $toUserId) {
            $query->where('from_id', $toUserId)->where('to_id', $fromUserId);
        })->take(50)->orderBy('created_at','DESC')->get();

        $message_html = '';
        foreach ($messages as $item){
            $message_class = $item->from_id == $id ? 'chat-left' : '';
            $message_class2 = $item->from_id == $id ? 'left' : 'right';
            $avatar = isset($item->from_user->image) ? asset('public'.$item->from_user->image) : (isset($item->to_user->image) ? asset('public'.$item->to_user->image) : '');
            $formattedCreatedAt = $item->created_at->diffForHumans();
            $f_name = isset($item->from_user) && $item->from_user->name ? $item->from_user->name : '';
            $attachment_html = '';
            if($item->attachment):
               $file = json_decode($item->attachment);
                $attachment_html =       '<p class="file">
                                                                <a href="'.asset('storage/attachments/'.$file->new_name).'" target="_blank">'.$file->old_name.'</a> مشاهدة المرفق
                                                            </p>';
                                                        endif;
            $message_html .= '<div class="chat '.$message_class.'">
                                            <div class="chat-avatar">
                                                <a class="avatar" data-toggle="tooltip" href="#"
                                                   data-placement="'.$message_class2.'" title=""
                                                   data-original-title="'.$f_name.'">
                                                    <img src="'.$avatar.'"
                                                         alt="'.$f_name.'"
                                                    />
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content">
                                                    <p>'.$item->body.'</p>
                                                    '.$attachment_html.'
                                                    <small>'.$formattedCreatedAt.'</small>
                                                </div>
                                            </div>
                                        </div>';

        }
        return response()->json([
            'status' => true,
            'messages_html' => $message_html
        ]);
    }
}
