import {Link} from '@inertiajs/react';
import dayjs from "dayjs";

export default function Operation({operation: {id, scheduled_at, notified}}) {
  const secondsToDDHHMMSS = (seconds) => {
    const days = Math.floor(seconds / (3600 * 24));
    seconds %= 3600 * 24;
    const hours = Math.floor(seconds / 3600);
    seconds %= 3600;
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;

    return `${days}日 ${hours}時 ${minutes}分 ${remainingSeconds}秒`;
}
  const diff = dayjs(scheduled_at).diff(dayjs(), 'second');
  return (
      <div className="p-4 md:w-1/3">
        <Link href={route('operations.show', id)} className="cursor-pointer">
          <div className=
              {"h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden transition "
          + (diff < 0 ? 'bg-gray-200 hover:bg-gray-300' : 'bg-white hover:bg-blue-200')}>
            <div className="h-full flex flex-col p-6">
              <h1 className="">ID: {id}</h1>
              {notified && (
                  <div className="text-xs text-red-500">通知済み</div>
              )}
              <h2 className="tracking-widest text-xs title-font font-medium text-gray-400 sm:mb-3">
                  作業予定日時: {dayjs(scheduled_at).format('YYYY年MM月DD日 HH時mm分')}
              </h2>
              {
                  (diff > 0) && (
                      <div className="">
                        {secondsToDDHHMMSS(diff)}後
                      </div>
                  )}

            </div>
          </div>
        </Link>
      </div>

  );
}
