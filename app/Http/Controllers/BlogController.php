<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\follow;
use App\Models\like;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailing;
use Auth;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

     /**
     * @OA\Get(
     *      path="/api/v1/Viewusers",
     *      operationId="getAllUsers",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Query",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    public function ViewUser()
    {
        $user =User::all();
        return response()->json([
            'status' => 'List of all user',
            'users' => $user,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/Viewblog",
     *      operationId="getAllBlogs",
     *      tags={"Blogs"},
     *      summary="Get list of blogs",
     *      description="Returns list of blogs",
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Query",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    public function index()
    {
        $blog = Blog::all()->where('user_id',Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'blog' => $blog,
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/v1/Createblog",
     *      operationId="createBlog",
     *      tags={"Blogs"},
     *      summary="create new blog",
     *      description="create a new blog to the system",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="title",
     *          description="Blog must have a title to identify it.",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="content",
     *          description="Content of blog describe it",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="image",
     *          description="image is used to provide more info of blog",
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          )
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:75',
            'content' => 'required|string|max:255',
        ]);

        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::user()->id,
        ]);
        Mail::to($request->user())->send(new mailing());
        return response()->json([
            'status' => 'success',
            'message' => 'Blog addded successfully',
            'blog' => $blog,
        ]);
    }

      /**
     * @OA\Get(
     *      path="/api/v1/ShowSingleblog/{id}",
     *      operationId="getBlogById",
     *      tags={"Blogs"},
     *      summary="Get Blog using id",
     *      description="get a blog from the system",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Blog id you need.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    
    public function show($id)
    {
        $results = DB::table('blogs')->find($id);
        if ($results) {
            return response()->json(["Blogs"=>$results],200);
        }else {
            return response()->json(["Message"=>"No data found"],200);
        }
    }

     /**
     * @OA\Get(
     *      path="/api/v1/blogs/{id}/user",
     *      operationId="getBlogByUser",
     *      tags={"Blogs"},
     *      summary="Get Blog using user",
     *      description="create a blogs that belong to specified user",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="user id you need.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    public function getBlogByUser($id) 
    {   
        $results = DB::table('blogs')->where('user_id', $id)->get();
        if ($results->count()>0) {
            return response()->json(["Blogs"=>$results],200);
        }else {
            return response()->json(["Message"=>"No data found on this user"],200);
        }
    }

     /**
     * @OA\Put(
     *      path="/api/v1/Updateblog/{id}",
     *      operationId="blogUpdate",
     *      tags={"Blogs"},
     *      summary="modify Blog",
     *      description="modify a blogs that belong to specified logged in",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="title",
     *          description="title to replace with old.",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="content",
     *          description="content to replace with old.",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="image",
     *          description="new image of blog.",
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="id",
     *          description="blog id to be modified.",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="user",
     *          description="user id('owner of blog').",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:75',
            'content' => 'required|string|max:255',
        ]);

        $blog =Blog::find($id);
        $blog->title = $request->title;
        $blog->content= $request->content;
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully',
            'blog' => $blog,
        ]);
    }
     /**
     * @OA\Delete(
     *      path="/api/v1/Deleteblog/{id}",
     *      operationId="Blog Delete",
     *      tags={"Blogs"},
     *      summary="delete Blog",
     *      description="delete your blogs using blog id",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="blog id you need to remove.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */
    public function destroy($id)
    {
        $blog =Blog::find($id);
        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted ',
            'blog' => $blog,
        ]);
    }

    
    /**
     * @OA\Post(
     *      path="/api/v1/follow/{id}",
     *      operationId="follow",
     *      tags={"Users"},
     *      summary="follow user",
     *      description="follow user using user id",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="id of user to like.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */

    function follow($id){
        $user_id=Auth::user()->id;
        if ($id == $user_id) {

            return response()->json([
                'message' => 'Invalid id '.$id.' ',
            ]);

        }else{
            $user=follow::all()->where('user',$user_id)->where('follow',1)->where('followed',$id);
            $count=collect($user)->count();
            if($count == 1){
                
                $follow = follow::create([
                    'follow' => 1,
                    'user' =>$user_id,
                    'followed' =>$id,
                ]);
                
                return response()->json([
                    'message' => 'User is followed successfully !',
                ]);

            }else{
                return response()->json([
                    'message' => 'Invalid user id  !',
                ]);
            }
            
        }
        
    }

    /**
     * @OA\Post(
     *      path="/api/v1/unfollower/{id}",
     *      operationId="userFollowers",
     *      tags={"Users"},
     *      summary="followers of user",
     *      description="follower of user using user id",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="id of user to get her/him followers.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */

    function unfollow($id){
        $user_id=Auth::user()->id;
        $unfoll=follow::where('user',$user_id)->where('follow',1)->where('followed',$id);
        $unfoll->delete();

        $user=user::all()->where('id',$id);
        return response()->json([
                'message' => 'User is unfollowed successfully !',
            ]);

    }

     /**
     * @OA\Post(
     *      path="/api/v1/likeblog/{id}",
     *      operationId="likeBlog",
     *      tags={"Blogs"},
     *      summary="like Blog",
     *      description="like blogs using blog id",
     *      security={{"bearer":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="id of blog to like.",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad user Input",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     *     )
     */

    function like(Request $request,$id){
        $user_id=Auth::user()->id;
        $countNum=0;
        $likes=like::all()->where('blog_id',$id)->where('like',1)->where('user_id',$user_id)->where('count',1);
        $liking=collect($likes)->count();
        if($liking == 0){
            $likes=like::create([
                'like'=>1,
                'blog_id'=>$id,
                'user_id'=>$user_id,
                'count'=>$countNum+1,
            ]);
            $likenumber=like::all()->where('blog_id',$id)->where('like',1)->where('count',1);
            $countlikenumber=collect($likenumber)->count(); 
            
            $blogss=blog::all()->where('id',$id);
            
            return response()->json([
                'message'=>'blog liked !',
                'blog'=>$blogss,
                'like'=>$countlikenumber,
            ]);

        }else{
            $like =like::where('blog_id',$id)->where('like',1)->where('user_id',$user_id)->update(["count"=>$countNum]);
            if($like){
                $likenumbers=like::all()->where('blog_id',$id)->where('like',1)->where('count',1);
                $countlikenumbers=collect($likenumbers)->count();

                $blogsss=blog::all()->where('id',$id);

                return response()->json([
                    'message'=>'blog unliked !',
                    'blog'=>$blogsss,
                    'like'=>$countlikenumbers,
                ]);
            }
            
        }      
    }
}