import React from 'react'
import styled from 'styled-components'

const StyledNav = styled.aside`
  z-index: 10;
`

export const SideNav = () => (
  <StyledNav className='bx--side-nav'>
    <div className='bx--side-nav__content'>
      <header className='bx--side-nav__title-bar'>
        <div className='bx--side-nav__icon'>
          IconINCON
        </div>
        <div className='bx--side-nav__title-details'>
          <div className='bx--side-nav__title'>
            [L1 name here]
          </div>
        </div>
      </header>
      <div className='bx--side-nav__scroll'>
        <nav className='bx--side-nav__nav'>
          <ul className='bx--side-nav__nav-items'>
            <li className='bx--side-nav__nav-item'>
              <div className='bx--side-nav__category'>
                <div className='bx--side-nav__category-header'>
                  <div className='bx--side-nav__icon'>
                    <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'
                      aria-hidden='true'>
                      <path
                        d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
                    </svg>
                  </div>
                  <div className='bx--side-nav__category-title'>
                    Category label
                  </div>
                </div>
                <ul className='bx--side-nav__category-items'>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <li className='bx--side-nav__nav-item'>
              <div className='bx--side-nav__category bx--side-nav__category--active'>
                <div className='bx--side-nav__category-header'>
                  <div className='bx--side-nav__icon'>
                    <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'
                      aria-hidden='true'>
                      <path
                        d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
                    </svg>
                  </div>
                  <div className='bx--side-nav__category-title'>
                    Category label
                  </div>
                </div>
                <ul className='bx--side-nav__category-items'>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item bx--side-nav__category-item--active'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <li className='bx--side-nav__nav-item'>
              <div className='bx--side-nav__category'>
                <div className='bx--side-nav__category-header'>
                  <div className='bx--side-nav__icon'>
                    <svg width='20' height='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'
                      aria-hidden='true'>
                      <path
                        d='M8.24 25.14L7 26.67a14 14 0 0 0 4.18 2.44l.68-1.88a12 12 0 0 1-3.62-2.09zm-4.05-7.07l-2 .35A13.89 13.89 0 0 0 3.86 23l1.73-1a11.9 11.9 0 0 1-1.4-3.93zm7.63-13.31l-.68-1.88A14 14 0 0 0 7 5.33l1.24 1.53a12 12 0 0 1 3.58-2.1zM5.59 10L3.86 9a13.89 13.89 0 0 0-1.64 4.54l2 .35A11.9 11.9 0 0 1 5.59 10zM16 2v2a12 12 0 0 1 0 24v2a14 14 0 0 0 0-28z' />
                    </svg>
                  </div>
                  <div className='bx--side-nav__category-title'>
                    Category label
                  </div>
                </div>
                <ul className='bx--side-nav__category-items'>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                  <li className='bx--side-nav__category-item'>
                    <a className='bx--side-nav__link' href='javascript:void(0)'>
                      Nested link
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
      </div>
      <footer
        className='bx--side-nav__footer'>
        <button
          className='bx--side-nav__toggle'>
          <div
            className='bx--side-nav__icon'>
            ICON ICON
          </div>
          <span
            className='bx--assistive-text'>
  Toggle
the
expansion
state
of
the
navigation
          </span>
        </button>
      </footer>
    </div>
  </StyledNav>
)

export default SideNav
