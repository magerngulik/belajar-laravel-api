<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Pembahasan video ke 3

pada video [ketiga](https://www.youtube.com/watch?v=NEhPRiYPmkI&list=PLnrs9DcLyeJSfhHHbze8NfaHFh55HNBSh&index=3) beberapa materi sebagai berikut:
belajar tentang [api resources](https://laravel.com/docs/10.x/eloquent-resources) yang akan memberikan fungsi untuk mempermudah proses pembuatan response api
untuk membuat response api biasa nya seperti:

```
public function index(){
        $data = ["data" => Post::all(),];
        return response()->json($data, 200);
    }
```

sedangkan jika kita menggunakan api resources seperti berikut:
```
public function index(){
    return PostResource::collection(Post::all());
}
```
kelebihan dari penggunaa api resorces ini adalah kita bisa mengatur variable apa saja yang di kembalikan, langkah untuk setup nya seperti di bawah ini:

**membuat file resources**
```
php artisan make:resource UserResource
```
**setup response dari api**
```
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'news_content' => $this->title,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at))
        ]; 
        
    }
}
```

pada penjelasan di atas menjelaskan tentang melakukan request get untuk menggambil sebuah data secara keseluruhan, bagaimana cara untuk menggambil single data dan mengembalikan response nya. berikut ini merupakan langkah langkah nya:


**Bagian Route**
```
Route::get('/posts2/{id}', [PostController::class, 'show2']);
```
**Bagian Controller yang menggunakan relasi**
```   
public function show($id){
        $post = Post::with('writter:id,username')->findOrFail($id);
        return  new PostDetailResorce($post);
    }
```


**Bagian Controller yang tidak menggunakan relasi**
```      
public function show2($id){
        $post = Post::findOrFail($id);
        return  new PostDetailResorce($post);
}
```

untuk bagian ini memiliki relasi ke tabel user, jadi untuk return data di harapkan memiliki nama author, ini akan memiliki masalah ketika menggunakan *api resource* yang sama akan membalikan semua data sementara data itu mungkin tidak di perlukan untuk mengatasi hal tersebut maka perlu di lakukan *eager loading* untuk menggunakan nya sebagai berikut:

**Bagian Resources**
```
class PostDetailResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'news_content' => $this->title,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'author' => $this->user_id,
            'writter' => $this->whenLoaded('writter') 
        ];
    }
}
```
Dari koding di atas penggunaan *eager loading*  ada pada baris ini **'writter' => $this->whenLoaded('writter')** jadi bagian ini hanya akan di jalankan ketia ada relasi ke tabel user dengan kode **Post::with('writter:id,username')->findOrFail($id);**, kode ini melakukan relasi ke tabel user yang hanya mengembalikan data berupa id dan username. untuk koding relasi nya pada bagian model seperti berikut:
**kode relasi**

```
 public function writter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
```





