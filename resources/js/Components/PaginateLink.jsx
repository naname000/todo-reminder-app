import { Link } from '@inertiajs/react';

export default function PaginateLink({ link }) {
    if (link.url === null) {
        return (
            <li className="px-2 py-1 text-gray-400 cursor-default" aria-disabled="true">
                <span dangerouslySetInnerHTML={{ __html: link.label }} />
            </li>
        );
    }

    return (
        <li>
            <Link
                href={link.url}
                className={`px-3 py-1 rounded-md border text-sm mx-1
                    ${link.active
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'text-gray-700 border-gray-300 hover:bg-gray-100'}
                `}
                dangerouslySetInnerHTML={{ __html: link.label }}
            />
        </li>
    );
}
