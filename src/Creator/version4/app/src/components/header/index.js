import React from 'react'
import styled from 'styled-components'

const StyledHeader = styled.header`
  z-index: 10;
`

export const Header = () => (
  <StyledHeader className='bx--platform-header'>
    <button className='bx--platform-header__menu-trigger bx--platform-header__action' title='Open menu'>
      <svg aria-hidden='true' width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'>
        <path d='M4 6h24v2H4zm0 18h24v2H4zm0-9h24v2H4z' />
      </svg>
    </button>
    <a className='bx--platform-header__name' href='#' title=''>
      IBM <span className='bx--platform-header__platform-name'>[Platform]</span>
    </a>
    <nav className='bx--platform-header__nav'>
      <ul className='bx--platform-header__links'>
        <li className='bx--platform-header__link-item'>
          <a href='javascript:void(0)' className='bx--platform-header__link'>
            L1 link 1
          </a>
        </li>
        <li className='bx--platform-header__link-item'>
          <a href='javascript:void(0)' className='bx--platform-header__link'>
            L1 link 2
          </a>
        </li>
        <li className='bx--platform-header__dropdown-item' tabIndex='0'>
          L1 link 3
          <svg className='bx--platform-header__arrow' width='12' height='7'>
            <path d='M6.002 5.55L11.27 0l.726.685L6.003 7 0 .685.726 0z' />
          </svg>
          <ul className='bx--dropdown-item__menu'>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 1</a></li>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 2</a></li>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 3</a></li>
          </ul>
        </li>
        <li className='bx--platform-header__dropdown-item' tabIndex='0'>
          L1 link 4
          <svg className='bx--platform-header__arrow' width='12' height='7'>
            <path d='M6.002 5.55L11.27 0l.726.685L6.003 7 0 .685.726 0z' />
          </svg>
          <ul className='bx--dropdown-item__menu'>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 1</a></li>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 2</a></li>
            <li className='bx--dropdown-item__menu-item'><a href='#'>Link 3</a></li>
          </ul>
        </li>
      </ul>
    </nav>
    <div className='bx--platform-header__global'>
      <button className='bx--platform-header__action' title='Action 1'>
        <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' aria-hidden='true'>
          <path
            d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
        </svg>
      </button>
      <button className='bx--platform-header__action' title='Action 2'>
        <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' aria-hidden='true'>
          <path
            d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
        </svg>
      </button>
      <button className='bx--platform-header__action' title='Action 3'>
        <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' aria-hidden='true'>
          <path
            d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
        </svg>
      </button>
      <button className='bx--platform-header__action' title='Action 4'>
        <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' aria-hidden='true'>
          <path
            d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
        </svg>
      </button>
    </div>
  </StyledHeader>
)

export default Header
