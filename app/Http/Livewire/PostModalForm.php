<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\UserPost;
use Illuminate\Support\Str;
use Livewire\Component;

class PostModalForm extends Component
{
    public $showModal = false;
    public $mode = 'create'; // create | edit
    public $postId = null;
    public $title = 'x';
    public $content = 'Pero este no cambia para nada';
    public $status = [];
    public $tags = [];
    public $links = [];
    public $redirect = null;
    public $editorId = 'editor_create';

    protected $listeners = [
        'postModalForm' => 'handleOpenModal',
        'quillContentUpdated' => 'updateContent'
    ];

    protected $rules = [
        'title' => ['required', 'string', 'min:3', 'max:200'],
        'content' => ['required', 'string', 'min:3'],
        'status' => ['nullable', 'array'],
        'status.*' => ['string', 'max:20'],
        'tags' => ['nullable', 'array'],
        'tags.*' => ['string', 'max:20'],
        'links' => ['nullable', 'array'],
        'links.*' => ['nullable', 'url'],
    ];

    public function handleOpenModal($data = [])
    {
        $this->resetErrorBag();
        $this->resetForm();

        $this->mode = $data['mode'] ?? 'create';
        $this->postId = $data['postId'] ?? null;
        $this->redirect = $data['redirect'] ?? null;

        if ($this->mode === 'edit' && $this->postId) {
            $post = Post::find($this->postId);
            if ($post) {
                $this->title = $post->title;
                $this->content = $post->content;
                $this->status = $post->status ?? [];
                $this->tags = $post->tags ?? [];
                $this->links = $post->links ?? [];
                $this->editorId = $data['editorId'] ?? Str::slug($this->mode.$post->id.now()->timestamp);
                $this->emit('updateQuillContent', $this->content);
            }
        } else {
            $this->editorId = $data['editorId'] ?? Str::slug($this->mode.now()->timestamp);
            $this->resetForm();
        }

        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset(['title','content','status','tags','links']);
    }

    public function updateContent($editorId, $value)
    {
        $this->content = $value;
    }

    public function save()
    {
        $this->validate();

        if ($this->mode === 'create') {
            $new_post = Post::create([
                'url_access' => Str::slug($this->title.'-'.now()),
                'descripcion' => $this->title,
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'tags' => $this->tags,
                'links' => $this->links,
            ]);

            UserPost::create([
                'user_id' => auth()->user()->id,
                'post_id' => $new_post->id,
            ]);

            toastr()->addSuccess('Publicación creada correctamente');

            sweetalert()
                ->showConfirmButton(
                true,
                    "Enterado",
                    "btn btn-success",
                    "Enterado"
                )
                ->addSuccess('Publicación creada correctamente');
            //session()->flash('success', 'Publicación creada correctamente.');
        } else {
            $post = Post::find($this->postId);
            $post->update([
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'tags' => $this->tags,
                'links' => $this->links,
            ]);

            toastr()->addSuccess('Publicación actualizada correctamente');

            sweetalert()
                ->showConfirmButton(
                true,
                    "Enterado",
                    "btn btn-success",
                    "Enterado"
                )
                ->addSuccess('Publicación actualizada correctamente');

            //session()->flash('success', 'Publicación actualizada correctamente.');
        }

        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }

        $this->resetForm();
        $this->showModal = false;
        $this->emit('refreshPosts');
    }

    public function render()
    {
        return view('livewire.post-modal-form');
    }
}
