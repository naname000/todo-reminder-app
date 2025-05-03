import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/react';
import dayjs from "dayjs";

export default function Dashboard({auth, today_operations, next_business_day_operations}) {
  return (
      <AuthenticatedLayout
          user={auth.user}
          header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
      >
        <Head title="Dashboard"/>

        <div className="py-12">
          <div className="container mx-auto px-6 mb-8">
            <h2 className="mb-4 text-lg sm:text-2xl font-extrabold dark:text-white">
              今日の予定
              {today_operations.length === 0 && <span className="text-gray-500">（予定はありません）</span>}
            </h2>
            <ul className={"flex flex-col gap-4"}>
              {today_operations.map(operation => {
                return (
                    <li key={operation.id}>
                        <Link href={route('operations.show', operation.id)} className="block truncate text-blue-500 hover:text-blue-700">
                            {dayjs(operation.scheduled_at).format('(YYYY年MM月DD日)HH時mm分')}
                            &nbsp;{operation.content}
                        </Link>
                    </li>
                );
              })}
            </ul>
          </div>
          <div className="container mx-auto px-6">
            <h2 className="mb-4 text-lg sm:text-2xl font-extrabold dark:text-white">
              次営業日の予定
              {next_business_day_operations.length === 0 && <span className="text-gray-500">（予定はありません）</span>}
            </h2>
            <ul className={"flex flex-col gap-4"}>
              {next_business_day_operations.map(operation => {
                return (
                    <li key={operation.id}>
                        <Link href={route('operations.show', operation.id)} className="block truncate text-blue-500 hover:text-blue-700">
                            {dayjs(operation.scheduled_at).format('(YYYY年MM月DD日)HH時mm分')}
                            &nbsp;{operation.content}
                        </Link>
                    </li>
                );
              })}
            </ul>
          </div>
        </div>
      </AuthenticatedLayout>
  );
}
