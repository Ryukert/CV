// GET /health — estado del servicio y fecha de la última actualización del CV.
import { CV_UPDATED } from '@/lib/cv.ts';

export const dynamic = 'force-dynamic';

export function GET() {
  return Response.json({ status: 'ok', cv_updated: CV_UPDATED }, { headers: { 'Cache-Control': 'no-store' } });
}
