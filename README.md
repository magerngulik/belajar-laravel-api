<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### video ke 3

pada video [ketiga](https://www.youtube.com/watch?v=NEhPRiYPmkI&list=PLnrs9DcLyeJSfhHHbze8NfaHFh55HNBSh&index=3) beberapa materi sebagai berikut:
- belajar tentang [api resources](https://laravel.com/docs/10.x/eloquent-resources) yang akan memberikan fungsi untuk mempermudah proses pembuatan response api
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