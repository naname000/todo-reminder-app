// OperationモデルのEdit画面を作成します
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import {useForm} from '@inertiajs/react';
import dayjs from "dayjs";
import timezone from "dayjs/plugin/timezone";
import utc from "dayjs/plugin/utc";
dayjs.extend(utc);
dayjs.extend(timezone);

export default function Edit({operation, auth}) {
  const {data, setData, post, patch, processing, errors, reset, transform} = useForm({
    id: operation.id,
    content: operation.content,
    scheduled_at: operation.scheduled_at,
    notified: operation.notified,
  });
  // JSTからUTCへ変換する
  transform((data) => ({
    ...data,
    scheduled_at: new Date(data.scheduled_at).toISOString(),
  }));
  const submit = (e) => {
    e.preventDefault();
    //patchで更新する
    patch(route('operations.update', data));
  };
  return (
      <AuthenticatedLayout user={auth.user} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Operation Edit</h2>}>
    <Head title="Operation Edit"/>
    <div className="py-12 px-4">
      <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <form onSubmit={submit}>
            <div className="p-6 bg-white">
              <div className="mb-4">
                <label htmlFor="scheduled_at" className="block text-gray-700 text-sm font-bold mb-2">作業予定日時</label>
                <input id="scheduled_at" name="scheduled_at" type="datetime-local" value={dayjs(data.scheduled_at).tz('Asia/Tokyo').format('YYYY-MM-DDTHH:mm')} onChange={(e) => setData('scheduled_at', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                {errors.scheduled_at && (<p className="text-red-500 text-xs italic">{errors.scheduled_at}</p>)}
              </div>
              <div className="mb-4">
                <label htmlFor="content" className="block text-gray-700 text-sm font-bold mb-2">作業内容</label>
                <textarea id="content" name="content" value={data.content} onChange={(e) => setData('content', e.target.value)} className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="10"/>
                {errors.content && (<p className="text-red-500 text-xs italic">{errors.content}</p>)}
              </div>
              <div className="flex items-center justify-center">
                <button type="submit" className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" disabled={processing}>
                  {processing ? (<div>更新中</div>) : (<div>更新</div>)}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div className="flex justify-center">
      <button onClick={() => window.history.back()} className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        戻る
      </button>
    </div>
  </AuthenticatedLayout>);
}