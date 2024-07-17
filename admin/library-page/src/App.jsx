import React, { useState, useEffect } from 'react';
import Book from './book';
import BookForm from './book-form';
import './App.css';

function App() {
  const [books, setBooks] = useState([]);
  const [form, setForm] = useState({
    book_id: '',
    title: '',
    author: '',
    publisher: '',
    isbn: '',
    publication_date: ''
  });
  const [openForm, setOpenForm] = useState(false);
  const [editing, setEditing] = useState(false);
  const [bookId, setBookId] = useState(null);

  useEffect(() => {
    fetchBooks();
  }, []);

  const fetchBooks = async () => {
    try {
      const response = await fetch(`${window.wpApiSettings.root}library/v1/books`);
      const data = await response.json();
      setBooks(data);
    } catch (error) {
      new Error(error);
    }
  };

  const onInputChange = (e) => {
    
    setForm({
      ...form,
      [e.target.name] : e.target.value
    })
  }

  //On click add new book
  const onClickAddNew = () => {
    setOpenForm(true);
    setEditing(false);
  }

  //on click create new 
  const onClickFormBtn = () => {
    console.log(form);
    createBook().then( () => {
      //empty form
      setForm({
        book_id: '',
        title: '',
        author: '',
        publisher: '',
        isbn: '',
        publication_date: ''
      });

      //fetch data
      fetchBooks();

      //close form
      setOpenForm(false);
    } );
    
  }

  const createBook = async () => {
    try{
      await fetch(`${window.wpApiSettings.root}library/v1/create_book`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.wpApiSettings.nonce
        },
        body: JSON.stringify( form )
      });
      
    }catch(error){
      new Error(error);
    }
  };

  //on click update 
  const onUpdateClick = ( bookID ) => {
    fetchBook(bookID);
    console.log(form);
    setOpenForm(true);
  }

  //fetch single book
  const fetchBook = async ( bookID ) => {
    try {
      const response = await fetch(`${window.wpApiSettings.root}library/v1/book/${bookID}`);
      const data = await response.json();
      setForm(data);
    } catch (error) {
      new Error(error);
    }
  };

  const updateBook = async ( bookID ) => {
    try{
      const response = await fetch(`${window.wpApiSettings.root}library/v1/book/${bookID}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.wpApiSettings.nonce
        },
        body: JSON.stringify( form )
      });

      const data = await response.json();
      console.log(data);
      
    }catch(error){
      new Error(error);
    }
  }

  const onDeleteClick = () => {
    console.log('delete');
  }



  return (
    <div className="wp-library-area py-20 max-w-[800px]">
      <div className="flex justify-end mb-4">
        <button className="py-2 px-4 bg-green-700 text-white" onClick={onClickAddNew}>Add New Book</button>
      </div>
      { openForm && <BookForm isUpdate={false} handleFormBtn={onClickFormBtn} onHandleChange={onInputChange} formValue={form} /> }
      <table className="w-full">
          <thead>
            <tr>
              <th>Book ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Publisher</th>
              <th>ISBN</th>
              <th>Publication Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            { books.map( item => (
              <Book key={item.book_id} book={item} handleUpdate={onUpdateClick} handleDelete={onDeleteClick} />
            ) ) }
          </tbody>
        </table>
    </div>
  );
}

export default App;
