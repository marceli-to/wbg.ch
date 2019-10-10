<?php
namespace App\Http\Controllers\Backend\Content;

use App\Services\MediaService;
use App\Models\Content;
use App\Http\Resources\ContentCollection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
  protected $mediaService;

  protected $content;

  /**
   * Constructor
   * 
   * @param MediaService $mediaService
   * @param Content $content
   */
  public function __construct(MediaService $mediaService, Content $content)
  {
    $this->mediaService = $mediaService;
    $this->content = $content;
  }

  /**
   * Get all jobs
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    $contents = $this->content->get();
    return new ContentCollection($contents);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {   
    $content = new Content([
      'key'  => $request->input('key'),
      'text' => $request->input('text'),
    ]);

    $content->save();
    return response()->json(['contentId' => $content->id]);
  }

  /**
   * Edit a specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $content = $this->content->findOrFail($id);
    return response()->json($content);
  }

  /**
   * Update the status of the specified resource.
   *
   * @param  int  $id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update($id, Request $request)
  {
    $content = $this->content->findOrFail($id);
    $content->key  = $request->input('key');
    $content->text = $request->input('title');
    $content->save();
    return response()->json('successfully updated');
  }

  /**
   * Update the status of the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function status($id)
  {
    $content = $this->content->findOrFail($id);
    $content->publish = $content->publish == 0 ? 1 : 0;
    $content->save();
    return response()->json($content->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  str $filename
   * @return \Illuminate\Http\Response
   */
  public function unlink($filename)
  {
    $content = $this->content->where('media', $filename)->first();
    if ($content)
    {
      $content->media = null;
      $content->save();
    }
    $this->mediaService->delete($filename);
    return response()->json('successfully deleted');
  }

  /**
   * Get all pages
   *
   * @return \Illuminate\Http\Response
   */

  public function keys()
  {
    $keys = \Config::get('content');
    return response()->json($keys['content_keys']);
  }
}
