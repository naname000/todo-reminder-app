// OperationモデルのCRUD画面を作成する
//
// ここでは、OperationモデルのCRUD画面を作成します。
// まずは、Operationモデルの一覧画面を作成します。
//
// 一覧画面の作成
//
// まずは、一覧画面の作成から始めます。
// 一覧画面は、resources/js/Pages/Operations/Index.jsxに作成します。
// 以下のコードを、resources/js/Pages/Operations/Index.jsxに追加します。
//
// Path: resources/js/Pages/Operations/Index.jsx
// // Operationモデル一覧画面
// // 一覧画面には、次のリンクがあります。
// // ・編集画面へのリンク
// // ・削除ボタン
// // ・新規作成画面へのリンク
// // ・ページネーション
//
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/react';
import dayjs from 'dayjs';
import PaginateLink from "@/Components/PaginateLink.jsx";
import Operation from "@/Pages/Operation/Operation";

export default function Index({ operations, links, auth }) {
    return (
        <AuthenticatedLayout user={auth.user} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Operation List</h2>}>
            <Head title="Operation List" />

            <section className="py-12 px-4 sm:px-12 text-gray-600 body-font">
                <div className="container mx-auto">
                    <div className="sm:flex flex-wrap -m-4">
                        {operations.data.map(operation => {
                            return (
                            <Operation key={operation.id} operation={operation} />
                            )
                        })}
                    </div>
                </div>
            </section>


            <nav className="py-6">
                <ul className="list-style-none flex justify-center flex-wrap">
                    {operations.total > 0 && operations.links.map((link, index) => (
                        <div key={index}><PaginateLink index={index} link={link} length={operations.last_page} current={operations.current_page}/></div>
                    ))}
                </ul>
            </nav>
        </AuthenticatedLayout>
    )
}