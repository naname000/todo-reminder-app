import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head} from '@inertiajs/react';
import {useForm} from '@inertiajs/react';
export default function Create({auth}) {
  const {data, setData, post, processing, errors, reset, transform} = useForm({
    scheduled_at: '',
    content: '',
    notified: '',
  });

  //UTCに変換する
  transform((data) => ({
    ...data,
    scheduled_at: new Date(data.scheduled_at).toISOString(),
  }));

  function onSubmit(e) {
    e.preventDefault();
    post(route('operations.store'));
  }

  return (<AuthenticatedLayout user={auth.user} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Operation Create</h2>}>
        <Head title="Operation Create"/>
        <div className="py-12 px-4">
          <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form onSubmit={onSubmit}>
              <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="p-6 bg-white border-b border-gray-200">
                  <div className="mb-4">
                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="scheduled_at">
                      作業予定日時
                    </label>
                    <input className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.scheduled_at ? 'border-red-500' : ''}`} id="scheduled_at" type="datetime-local" value={data.scheduled_at} onChange={(e) => setData('scheduled_at', e.target.value)}/>
                    {errors.scheduled_at && <p className="text-red-500 text-xs italic">{errors.scheduled_at}</p>}
                  </div>
                  <div className="mb-4">
                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="content">
                      内容
                    </label>
                    <textarea className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-48 ${errors.content ? 'border-red-500' : ''}`} id="content" value={data.content} onChange={(e) => setData('content', e.target.value)}/>
                    {errors.content && <p className="text-red-500 text-xs italic">{errors.content}</p>}
                  </div>
                  <div className="flex items-center justify-center">
                    <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                      {processing ? (
                          <div>
                            作成中
                          </div> ) : (
                          <div>
                            作成
                          </div>
                      )}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div className="mt-8 flex justify-center">
            <button onClick={() => window.history.back()} className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
              戻る
            </button>
          </div>
        </div>
      </AuthenticatedLayout>
  );
}