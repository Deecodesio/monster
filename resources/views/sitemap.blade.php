<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($restaurants as $post)
        <url>
            <loc>{{ $post['slug'] }}</loc>
            <lastmod>{{ $post['created_at'] }}</lastmod>
           
        </url>
    @endforeach
</urlset>