import React from 'react';

const Book = ( { book, handleUpdate, handleDelete } ) => {

    return (
        <tr>
            <td>{book.book_id}</td>
            <td>{book.title}</td>
            <td>{book.author}</td>
            <td>{book.publisher}</td>
            <td>{book.isbn}</td>
            <td>{book.publication_date}</td>
            <td>
                <button className="py-1 px-3 mr-2 mb-t bg-green-700 text-white" onClick={() => handleUpdate( book.book_id )}>Update</button>
                <button className="py-1 px-3 bg-red-700 text-white" onClick={ () => handleDelete( book.book_id ) }>Delete</button>
            </td>
        </tr>
    )
}

export default Book;