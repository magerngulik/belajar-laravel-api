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
Dari koding di atas penggunaan *eager loading*  ada pada baris ini **'writter' => $this->whenLoaded('writter')** jadi bagian ini hanya akan di jalankan ketia ada relasi ke tabel user dengan kode **Post::with('writter:id,username')->findOrFail($id)**, kode ini melakukan relasi ke tabel user yang hanya mengembalikan data berupa *id dan username*. untuk koding relasi nya pada bagian model seperti berikut:
**kode relasi**

```
public function writter(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
```

# Penjelasan [Video ke4](https://www.youtube.com/watch?v=AgkKLIPTmIg&list=PLnrs9DcLyeJSfhHHbze8NfaHFh55HNBSh&index=4)
pada video ini akan menggunakan authentification dengan [laravel sanctum](https://laravel.com/docs/10.x/sanctum), beberapa fiture yang dapat di buat adalah login, get token dan logout, untuk langkah langkah nya sebagai berikut:

install laravel sanctum dari composer 
```
composer require laravel/sanctum
```
Jika menggunakan versi laravel terbaru laravel sudah tersedia laravel sanctum

### konfigurasi route
untuk melakukan konfigurasi route pada laravel sanctum kita memerlukan akses ke _middleware_ seperti berikut ini: 
```
Route::get('/posts', [PostController::class, 'index'])->middleware('auth:sanctum');
Route::get('/posts/{id}', [PostController::class, 'show'])->middleware(['auth:sanctum']);
```
untuk dokumentasi dari setup route di sanctume bisa di akses [disini](https://laravel.com/docs/10.x/sanctum#protecting-spa-routes), sedikit catatan dalam dokumentasi fungsi dari request dan penggunaan route posisi nya bisa di bagian belakang atau depan, ada perbedaan tapi fungsi dasar nya sama berikut contoh nya:

```
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
```

### cara akses api
untuk mengakses api yang menggunakan laravel sanctume kita perlu menambahkan beberapa item di bagian header seperti gambar berikut ini:
<img src="https://i.ibb.co/P5rXpsc/authorization-header.png" alt="gambar header">

jadi kita perlu menggirimkan pada bagian header *Accept* dan *Authorization*;
- **Accept** : application/json   
- **Authorization** : **Bearer** Token

Untuk bagian token didapatkan ketika proses login, diwebsite laravel sudah di jelaskan bagaimana cara membuat function login bisa di baca [disini](https://laravel.com/docs/10.x/sanctum#issuing-mobile-api-tokens)

### proses login
untuk proses login alur nya akan sebagai berikut:
- buat route untuk mengatur end point login dan buat controller untuk menghandle semua authorization
Route
```
Route::post('/login', [AuthentificationController::class, 'login']);
```
Controller
```
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthentificationController extends Controller
{
    function Login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $user->createToken('user login')->plainTextToken;
    }
}
```
langkah pertama yang di lakukan adalah melakukan adalah mengakses endpoint login, pada bagian header akan menggirimkan *Accept*, sedangkan pada bagian body nya kita akan menggirimkan email dan password, untuk lebih jelas nya lihat gambar berikut:
<br>
Gambar header
<img src="https://i.ibb.co/0qLqTZm/image.png" alt="Gambar Header">
Gambar body
<img src="https://i.ibb.co/GFDttvK/image.png" alt="Gambar Header">

Dalam function login ada beberapa hal yang di lakukan yaitu:
- melakukan validasi data
```
 $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
```
- melakukan check pada email apakan tersedia atau tidak
```
 $user = User::where('email', $request->email)->first();
```
- melakukan if yang memiliki fungsi mengecek kondisi user atau check password post dan password yang ada di dalam db, jika kondisi tida terpenuhi akan mengembalikan *throw*
```
  if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
```
- jika kondisi terpenuhi maka akan mengembalikan isi token, dalam function createToken harus berisi nama dari token nya
```
 return $user->createToken('user login')->plainTextToken;
```

hasil dari function akan mendapatkan token, token ini yang akan digunakan untuk membuka akses yang sebelum nya di block oleh laravel sanctum
**Sebelum menggunakan token**
<img src="https://i.ibb.co/nR4jmsX/image.png" alt="Gambar header sebelum menggunakan item">

**Sesudah menggunakan token**
<img src="https://i.ibb.co/mTRDwyX/image.png" alt="Gambar header susudah menggunakan token">

dibagian menggunakan token pada bagian *authorization* untuk value harus menggunakan kata kunci *Bearer* + token seperti contoh *Bearer 1|Jc2Rf9iJC0FmJSjF1e8QLkKVSQ5Frme1Ihe8pdoe*      





