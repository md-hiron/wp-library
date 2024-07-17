import React from "react";

const BookForm = ({ isUpdate, handleFormBtn, onHandleChange, formValue }) => {
  return (
    <div className="book-form-area max-w-[400] bg-white p-10 my-10">
      <div className="book-form-wrap">
        <div className="form-field mb-2">
          <input
            type="text"
            value={formValue.title}
            name="title"
            onChange={onHandleChange}
            placeholder="Title"
          />
        </div>
        <div className="form-field mb-2">
          <input
            type="text"
            value={formValue.author}
            name="author"
            onChange={onHandleChange}
            placeholder="Author"
          />
        </div>
        <div className="form-field mb-2">
          <input
            type="text"
            value={formValue.publisher}
            name="publisher"
            onChange={onHandleChange}
            placeholder="Publisher"
          />
        </div>
        <div className="form-field mb-2">
          <input
            type="text"
            value={formValue.isbn}
            name="isbn"
            onChange={onHandleChange}
            placeholder="ISBN"
          />
        </div>
        <div className="form-field mb-2">
          <input
            type="date"
            value={formValue.publication_date}
            name="publication_date"
            onChange={onHandleChange}
          />
        </div>
        <div className="book-form-btn mt-4">
          <button
            onClick={handleFormBtn}
            className="py-2 px-4 bg-green-700 text-white"
          >
            {isUpdate ? "Update" : "Create"}
          </button>
        </div>
      </div>
    </div>
  );
};

export default BookForm;
