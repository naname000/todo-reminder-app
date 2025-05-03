import NavLink from "@/Components/NavLink.jsx";

export default function PaginateLink({link, index, length, current}) {
    if (index < 2 || length - 1 < index || index === current) {
        return (
            <li>
                <NavLink
                    className="relative block rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white"
                    href={link.url}
                    active={link.active}>
                    <span dangerouslySetInnerHTML={{__html: link.label}}/>
                </NavLink>
            </li>
        );
    } else {
        return (
            <div key={link.label}>.</div>
        )
    }
}