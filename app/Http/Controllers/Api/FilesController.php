<?php

namespace App\Http\Controllers\Api;

use App\Models\FilesContent;
use App\Models\FilesUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Files as FilesResource;
use Illuminate\Support\Facades\Response;

class FilesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $files = Files::query();
        $files->addSelect([
            'files.id',
            'files.name',
            'files.type',
            'files.description',
            'files_user.user_id',
            'files_content.content',
            'files.created_at',
            'files.updated_at'
        ]);
        $files->join('files_user','files_user.file_id','=','files.id');
        $files->join('files_content','files_content.file_id','=','files.id');

        //if the user does not have admin permission, they can only see their own files.
        if(empty($user->hasRole('admin'))){
            $files->where('files_user.user_id',$user->id);
        }

        return $this->sendResponse(FilesResource::collection($files->get()), 'Files retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();

        $validator = Validator::make($input, [
            'name' => 'required',
            'content' => 'required|mimes:pdf|max:10000'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        //get the original file name
        $fileName = $request->file('content')->getClientOriginalName();
        $fileType = $request->file('content')->getClientMimeType();
        $argsFiles = [
            'name' => $input['name'],
            'description' => $input['description'],
            'filename' => $fileName,
            'type' => $fileType
        ];

        try {
            $error = null;
            $files = Files::create($argsFiles);
        } catch (\Exception $exception) {
            error_log(json_encode($exception));
            $error = ['exception' => $exception->getMessage()];
            $files = null;
        }

        if (empty($files)){
            $error = $error ?: ['unknown' => 'Error when saving this new file'];
            return $this->sendError('Insert Error.', $error);

        }else{

            //insert files_user table
            $argsUser = [
                'file_id' => $files->id,
                'user_id' => $user->id
            ];

            $filesUser = FilesUser::create($argsUser);
            $files->user_id = $filesUser->user_id;  //include in resource files

            //converts to base64_encode and insert files_content table
            $fileContent = $request->file('content')->getContent();
            $fileBase64 = base64_encode($fileContent);

            $argsFilesContent = [
                'file_id' => $files->id,
                'content' => $fileBase64
            ];

            $filesContent = FilesContent::create($argsFilesContent);
            $files->content = $filesContent->content; //include in resource files
        }

        return $this->sendResponse(new FilesResource($files), 'Files created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $files = Files::find($id);

        if (is_null($files)) {
            return $this->sendError('Files not found.');
        }

        return $this->sendResponse(new FilesResource($files), 'Files retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Files $files)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $files->name = $input['name'];
        $files->description = $input['description'];
        $files->save();

        return $this->sendResponse(new FilesResource($files), 'Files updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Files $files)
    {
        $files->delete();

        return $this->sendResponse([], 'Files deleted successfully.');
    }
}
