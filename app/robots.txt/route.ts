import { url } from '@/lib/cv.ts';

export async function GET() {
  return new Response(`User-agent: *\nAllow: /\n\nSitemap: ${await url('/sitemap.xml')}\n`, {
    headers: { 'Content-Type': 'text/plain; charset=utf-8', 'Cache-Control': 'public, max-age=86400' },
  });
}
