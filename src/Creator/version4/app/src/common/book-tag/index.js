import React from 'react'
import { Tag } from 'carbon-components-react'

const bookType = book => {
  switch (book) {
    case 'EP':
      return 'ibm'
    case 'RW':
      return 'third-party'
    case 'PAN':
      return 'local'
    case 'SW':
      return 'experimental'
    case 'GC':
      return 'community'
    case 'TH':
      return 'private'
    default:
      return 'beta'
  }
}

const BookTag = ({ book }) => (<Tag type={bookType(book)}>{book}</Tag>)

export default BookTag
