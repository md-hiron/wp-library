import React from 'react';

const Book = ( { book, handleUpdate, handleDelete } ) => {

    return (
        <tr className="even:bg-gray-200">
            <td className="p-3">{book.book_id}</td>
            <td className="p-3">{book.title}</td>
            <td className="p-3">{book.author}</td>
            <td className="p-3">{book.publisher}</td>
            <td className="p-3">{book.isbn}</td>
            <td className="p-3">{book.publication_date}</td>
            <td className="p-3">
                <button className="py-1 px-3 mr-2 mb-t bg-green-700 text-white" onClick={() => handleUpdate( book.book_id )}>Update</button>
                <button className="py-1 px-3 bg-red-700 text-white" onClick={ () => handleDelete( book.book_id ) }>Delete</button>
            </td>
        </tr>
    )
}

export default Book;