export const options = {
    credentials: 'include',
    headers: {
        'Content-Type': 'application/json',
        'X-Csrf-Token': document.head.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
    },
};
