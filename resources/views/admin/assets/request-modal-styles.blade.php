@once
<style>
    .request-modal .modal-content {
        border: 0;
        border-radius: .625rem;
        box-shadow: 0 1.5rem 3rem -1rem rgba(39, 44, 51, .28);
    }

    .request-modal .modal-header {
        align-items: flex-start;
        padding: 1.15rem 1.5rem;
        border-bottom: 1px solid #DCE7D8;
        background: #FFFDF7;
    }

    .request-modal .modal-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.35;
    }

    .request-modal .modal-body {
        padding: 1.4rem 1.5rem .6rem;
    }

    /* The view/edit form wraps header, body and footer inside scrollable
       dialogs, so it must be the flex column that constrains the body. */
    .request-modal .modal-content > form {
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
        min-height: 0;
    }

    .request-modal .modal-content > form > .modal-body {
        min-height: 0;
        overflow-y: auto;
    }

    .request-modal .modal-footer {
        padding: .95rem 1.5rem;
        border-top: 1px solid #DCE7D8;
        background: #F7F4EA;
        gap: .5rem;
    }

    .request-modal .modal-footer form {
        margin: 0;
        display: flex;
    }

    .request-modal .modal-footer > .btn-secondary {
        margin-right: auto;
    }

    .request-modal__glyph {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        width: 2.5rem;
        height: 2.5rem;
        margin-right: .9rem;
        border-radius: .55rem;
        background: rgba(43, 82, 56, .12);
        color: #2B5238;
    }

    .request-modal__glyph .material-icons {
        font-size: 1.25rem;
    }

    .request-modal__heading {
        min-width: 0;
    }

    .request-modal__meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin-top: .2rem;
        font-size: .75rem;
        color: #6B8F71;
    }

    .request-modal__meta > * + * {
        margin-left: .55rem;
    }

    .request-modal__ref {
        font-weight: 600;
        color: #152C1F;
        letter-spacing: .02em;
        font-variant-numeric: tabular-nums;
    }

    .request-modal__meta .badge {
        padding: .3em .7em;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .request-modal__section {
        padding-bottom: 1.35rem;
    }

    .request-modal__section + .request-modal__section {
        padding-top: 1.35rem;
        border-top: 1px solid #EDF1E7;
    }

    .request-modal__section-title {
        display: flex;
        align-items: center;
        margin-bottom: .85rem;
        font-family: "Exo 2", "Helvetica Neue", Arial, sans-serif;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: #5E7461;
    }

    .request-modal__section-title .material-icons {
        margin-right: .45rem;
        font-size: 1rem;
        color: #4F7D5C;
    }

    .request-modal__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .95rem 1.75rem;
        margin: 0;
    }

    .request-modal__grid--single {
        grid-template-columns: 1fr;
    }

    .request-modal__field {
        min-width: 0;
    }

    .request-modal__field--full {
        grid-column: 1 / -1;
    }

    .request-modal__label {
        margin-bottom: .2rem;
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #8FAE92;
    }

    .request-modal__value {
        margin: 0;
        font-size: .8125rem;
        line-height: 1.6;
        color: #152C1F;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .request-modal__value--muted {
        color: #9AAE9D;
    }

    .request-modal__chips {
        display: flex;
        flex-wrap: wrap;
        margin: -.15rem;
    }

    .request-modal__chip {
        display: inline-flex;
        align-items: center;
        margin: .15rem;
        padding: .3rem .6rem;
        border-radius: 2rem;
        background: #F1F5EC;
        color: #3B6B4A;
        font-size: .72rem;
        font-weight: 600;
    }

    .request-modal__file {
        display: flex;
        align-items: center;
        padding: .7rem .85rem;
        border: 1px solid #DCE7D8;
        border-radius: .45rem;
        background: #F7F4EA;
    }

    .request-modal__file + .request-modal__file {
        margin-top: .5rem;
    }

    .request-modal__file-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        width: 2.1rem;
        height: 2.1rem;
        margin-right: .75rem;
        border: 1px solid #DCE7D8;
        border-radius: .4rem;
        background: #FFFDF7;
        color: #6B8F71;
    }

    .request-modal__file-icon .material-icons {
        font-size: 1.1rem;
    }

    .request-modal__file-name {
        min-width: 0;
        font-size: .8rem;
        font-weight: 600;
        color: #152C1F;
    }

    .request-modal__panel {
        margin-bottom: 1.25rem;
        padding: 1rem 1.15rem;
        border: 1px solid #DCE7D8;
        border-radius: .5rem;
        background: #F7F4EA;
    }

    .request-modal .form-label {
        margin-bottom: .3rem;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: #6B8F71;
    }

    .request-modal .form-control {
        height: calc(1.5em + .9rem + 2px);
        padding: .45rem .75rem;
        font-size: .8125rem;
        border-color: #B9CDB6;
        border-radius: .375rem;
    }

    .request-modal textarea.form-control {
        height: auto;
    }

    .request-modal .form-control:focus {
        border-color: #4F7D5C;
        box-shadow: 0 0 0 .2rem rgba(79, 125, 92, .18);
    }

    .request-modal .form-text {
        font-size: .72rem;
        color: #8FAE92;
    }

    .request-modal .close {
        position: static;
        margin: -.25rem -.25rem 0 .75rem;
        padding: .25rem;
        font-size: 1.6rem;
        line-height: 1;
        opacity: .45;
    }

    .request-modal .close:hover {
        opacity: .8;
    }

    .request-modal__actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: .5rem;
        margin-left: auto;
    }

    .request-modal .btn {
        padding: .45rem .95rem;
        font-size: .8125rem;
        font-weight: 600;
        border-radius: .375rem;
    }

    .request-modal .btn .material-icons {
        margin-right: .3rem;
        font-size: 1rem;
        vertical-align: -.15rem;
    }

    @media (max-width: 575.98px) {
        .request-modal .modal-header,
        .request-modal .modal-body,
        .request-modal .modal-footer {
            padding-left: 1.1rem;
            padding-right: 1.1rem;
        }

        .request-modal__grid {
            grid-template-columns: 1fr;
            gap: .85rem;
        }

        .request-modal__actions {
            width: 100%;
            justify-content: stretch;
        }

        .request-modal__actions form,
        .request-modal__actions > .btn {
            flex: 1 1 auto;
        }

        .request-modal__actions form .btn {
            width: 100%;
        }
    }
</style>
@endonce
