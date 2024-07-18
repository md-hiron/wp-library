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
  const [totalPage, setTotalPage] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchTerm, setSearchTerm] = useState(null);

  useEffect(() => {
    fetchBooks();
  }, [currentPage]);

  const fetchBooks = async ( isSearch = false ) => {
    let param = `page=${currentPage}`;
    if( isSearch ){
      param += `&search=${searchTerm}`;
    }
    try {
      const response = await fetch(`${window.wpApiSettings.root}library/v1/books?${param}`);
      const data = await response.json();
      if( data ){
        setBooks(data.books);
        setTotalPage(data.total_pages)
        setCurrentPage(data.current_page)
      }
      
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
    setEditing(false);
  }

  //on click create new 
  const onClickFormBtn = async ( bookID ) => {
    if( ! editing ){
      await createBook();
    }else{
     await updateBook( bookID );
    }

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
    setEditing(true);
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
      await fetch(`${window.wpApiSettings.root}library/v1/books/${bookID}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.wpApiSettings.nonce
        },
        body: JSON.stringify( form )
      });
      
    }catch(error){
      new Error(error);
    }
  }

  const onDeleteClick = async ( bookID ) => {
    try{
      await fetch(`${window.wpApiSettings.root}library/v1/books/${bookID}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.wpApiSettings.nonce
        },
      });

      //fetch data
      fetchBooks();
      
    }catch(error){
      new Error(error);
    }
  }

  //next button
  const handleNextClick = () => {
    if( currentPage < totalPage ){
      setCurrentPage( currentPage + 1 )
    }
  }

  const handlePrevClick = () => {
    if( currentPage > 1 ){
      setCurrentPage( currentPage - 1 )
    }
  }

  const handleSearchChange = (e) => {
    const value = e.target.value;
    if( value.length > 0 ){
      setSearchTerm(e.target.value);
      fetchBooks(true);
    }else{
      setSearchTerm(null);
      fetchBooks(false);
    }
    
  }



  return (
    <div className="wp-library-area py-20 max-w-[900px]">
      <div className="flex justify-end mb-4">
        <button className="py-2 px-4 bg-green-700 text-white" onClick={onClickAddNew}>Add New Book</button>
      </div>
      { openForm && <BookForm isUpdate={editing} handleFormBtn={onClickFormBtn} onHandleChange={onInputChange} formValue={form} /> }
      <div className="search-form-area mt-10 mb-5">
        <div className="search-form-field">
          <input type="text" placeholder="Search by title, author, publisher or ISBN" onChange={handleSearchChange} />
        </div>
      </div>
      <table className="w-full">
          <thead>
            <tr>
              <th className="p-3 bg-gray-300 text-left">Book ID</th>
              <th className="p-3 bg-gray-300 text-left">Title</th>
              <th className="p-3 bg-gray-300 text-left">Author</th>
              <th className="p-3 bg-gray-300 text-left">Publisher</th>
              <th className="p-3 bg-gray-300 text-left">ISBN</th>
              <th className="p-3 bg-gray-300 text-left">Publication Date</th>
              <th className="p-3 bg-gray-300 text-left" >Action</th>
            </tr>
          </thead>
          <tbody>
            { books.map( item => (
              <Book key={item.book_id} book={item} handleUpdate={onUpdateClick} handleDelete={onDeleteClick} />
            ) ) }
          </tbody>
        </table>
        { books.length > 0 && 
          <div className="books-pagination mt-8">
            <button className="py-1 px-3 mr-2 bg-green-700 text-white" onClick={handlePrevClick} disabled={currentPage === 1 ? true : ''}>Prev</button>
            <span>{currentPage} / {totalPage}</span>
            <button className="py-1 px-3 ml-2 bg-green-700 text-white" onClick={handleNextClick} disabled={currentPage === totalPage ? true : ''}>Next</button>
          </div>
        }
    </div>
  );
}

export default App;
