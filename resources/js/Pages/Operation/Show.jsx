// Operationモデルの詳細を表示します
//
// scheduled_atの表示
// contentの表示
// notifiedの表示
// 編集ボタンの表示
// 戻るボタンの表示

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from '@inertiajs/react';
import dayjs from "dayjs";
import Linkify from 'linkify-react';

export default function Show({operation, auth}) {
  const deleteButtonHandler = (e) => {
    e.preventDefault();
    router.delete(route('operations.destroy', operation.id), {
      onBefore: () => confirm(`削除してもよろしいですか？`),
    });
  }
  return (<AuthenticatedLayout
      user={auth.user}
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Operation</h2>}
  >
    <Head title="Operation"/>

    <div className="py-12 px-4">
      <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div className="p-6 bg-white border-b border-gray-200">
            <div className="block text-gray-700 text-lg font-bold mb-2">作業予定日時</div>
            <div className="mb-5">{dayjs(operation.scheduled_at).format('YYYY年MM月DD日 HH時mm分')}</div>
            <div className="block text-gray-700 text-lg font-bold mb-2">作業内容</div>
            <div className="whitespace-pre-line">
              <Linkify options={{className: 'underline text-blue-500', target: '_blank'}}>
                {operation.content}
              </Linkify>
            </div>
            <div>{operation.notified}</div>
          </div>
        </div>
      </div>
    </div>
    <div className="flex justify-center gap-4">
      <Link href={route('operations.edit', operation.id)} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        編集
      </Link>
      <button onClick={deleteButtonHandler} className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        削除
      </button>
      <button onClick={() => window.history.back()} className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        戻る
      </button>
    </div>
  </AuthenticatedLayout>);
}
