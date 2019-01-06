export default async function(observer, response) {
    switch (response.status) {
    case 200:
        observer({
            type: 'success',
            title: '完了',
            text: '該当する書籍の登録に成功しました',
        });
        break;
    case 404:
        observer({
            type: 'warn',
            title: '見つかりません',
            text: '該当する書籍が見つかりませんでした',
        });
        break;
    case 408:
        observer({
            type: 'error',
            title: 'タイムアウトしました',
            text: '件数が多すぎるか、応答に時間がかかっています',
        });
        break;
    case 409:
        observer({
            type: 'error',
            title: '登録済み',
            text: '該当する書籍はすべて登録されています',
        });
        break;
    case 422:
        observer({
            type: 'error',
            title:'入力エラー',
            text: Object.values((await response.json()).errors)[0][0],
        });
        break;
    case 500:
        observer({
            type:'error',
            title:'内部エラー',
            text:'不明なエラーが発生しました。',
        });
        break;
    }
}
