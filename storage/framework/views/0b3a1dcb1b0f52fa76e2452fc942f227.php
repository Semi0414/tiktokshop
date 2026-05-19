
<style>
#ml-app *, #ml-app *::before, #ml-app *::after {
  box-sizing: border-box; margin: 0; padding: 0;
}
#ml-app {
  --ml-navy:      #0A1628;
  --ml-navy-h:    #1A2F55;
  --ml-blue:      #2563EB;
  --ml-blue-pale: #EFF4FF;
  --ml-teal:      #0E7490;
  --ml-teal-lt:   #CFFAFE;
  --ml-gold:      #C9972B;
  --ml-gold-lt:   #FEF3C7;
  --ml-rose:      #BE185D;
  --ml-rose-lt:   #FCE7F3;
  --ml-border:    #E2E8F4;
  --ml-border-s:  #C5D0E8;
  --ml-surface:   #F7F9FD;
  --ml-surface2:  #EEF2FA;
  --ml-text:      #0A1628;
  --ml-text2:     #3D5080;
  --ml-text3:     #7A8EBB;
  --ml-white:     #FFFFFF;
  --ml-ff:        'DM Sans', ui-sans-serif, system-ui, sans-serif;
  --ml-fm:        'DM Mono', ui-monospace, monospace;
  --ml-r:         8px;
  --ml-rl:        12px;
  font-family: var(--ml-ff);
  color: var(--ml-text);
  background: var(--ml-surface);
  border-radius: var(--ml-rl);
  min-height: 0;
}
#ml-app .ml-page   { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem 2rem; }
#ml-app .ml-topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; gap: 1rem; flex-wrap: wrap; }
#ml-app .ml-brand      { display: flex; align-items: center; gap: 10px; }
#ml-app .ml-brand-icon { width: 38px; height: 38px; background: var(--ml-navy); border-radius: var(--ml-r); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
#ml-app .ml-brand-icon svg { width: 18px; height: 18px; stroke: #fff; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
#ml-app .ml-brand-name { font-size: 16px; font-weight: 500; color: var(--ml-navy); letter-spacing: -0.01em; }
#ml-app .ml-brand-sub  { font-size: 11px; color: var(--ml-text3); text-transform: uppercase; letter-spacing: 0.05em; }
#ml-app .ml-back {
  display: inline-flex; align-items: center; font-size: 13px; color: var(--ml-blue);
  text-decoration: none; font-weight: 500; margin-bottom: 1rem;
}
#ml-app .ml-back:hover { text-decoration: underline; }
#ml-app .ml-divider { height: 1px; background: var(--ml-border); margin: 0 0 1.5rem; }
#ml-app .ml-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(115px, 1fr)); gap: 10px; margin-bottom: 1.5rem; }
#ml-app .ml-stat { background: var(--ml-white); border: 0.5px solid var(--ml-border); border-radius: 10px; padding: 14px 16px; position: relative; overflow: hidden; }
#ml-app .ml-stat::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; }
#ml-app .ml-stat.s-all::before   { background: var(--ml-navy); }
#ml-app .ml-stat.s-photo::before { background: var(--ml-blue); }
#ml-app .ml-stat.s-video::before { background: var(--ml-teal); }
#ml-app .ml-stat-label { font-size: 11px; color: var(--ml-text3); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 6px; }
#ml-app .ml-stat-val   { font-size: 26px; font-weight: 500; color: var(--ml-navy); font-family: var(--ml-fm); line-height: 1; }
#ml-app .ml-stat-tag   { font-size: 11px; color: var(--ml-text3); margin-top: 4px; }
#ml-app .ml-flash-ok { background: #ECFDF5; border: 0.5px solid #A7F3D0; color: #047857; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 1rem; }
#ml-app .ml-card { background: var(--ml-white); border: 0.5px solid var(--ml-border); border-radius: var(--ml-rl); overflow: hidden; margin-bottom: 1.25rem; }
#ml-app .ml-card-pad { padding: 1.5rem; }
#ml-app .ml-card-title { font-size: 15px; font-weight: 600; color: var(--ml-navy); margin-bottom: 1rem; }
#ml-app .ml-field { margin-bottom: 1.1rem; }
#ml-app .ml-field > label, #ml-app .ml-field label.ml-label { display: block; font-size: 12px; color: var(--ml-text2); font-weight: 500; margin-bottom: 6px; }
#ml-app .ml-field input[type="file"] { max-width: 100%; }
#ml-app .ml-field input[type="text"],
#ml-app .ml-field input[type="number"],
#ml-app .ml-field select { width: 100%; max-width: 20rem; padding: 8px 12px; font-size: 13px; font-family: var(--ml-ff); border: 0.5px solid var(--ml-border-s); border-radius: var(--ml-r); background: var(--ml-white); color: var(--ml-text); outline: none; }
#ml-app .ml-field input:focus, #ml-app .ml-field select:focus { border-color: var(--ml-blue); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
#ml-app .ml-hint { font-size: 12px; color: var(--ml-text3); margin-top: 6px; }
#ml-app .ml-field-error { font-size: 12px; color: #B91C1C; margin-top: 4px; }
#ml-app .ml-btn-primary { display: inline-flex; padding: 9px 20px; background: var(--ml-navy); color: #fff; border: none; border-radius: var(--ml-r); font-family: var(--ml-ff); font-size: 13px; font-weight: 500; cursor: pointer; transition: background .15s; }
#ml-app .ml-btn-primary:hover { background: var(--ml-navy-h); }
#ml-app .ml-dz { position: relative; border: 1.5px dashed var(--ml-border-s); border-radius: 10px; padding: 1.5rem 1rem; text-align: center; cursor: pointer; transition: all .15s; margin-bottom: 0.5rem; background: var(--ml-surface); display: block; }
#ml-app .ml-dz:hover, #ml-app label.ml-dz:focus-within { border-color: var(--ml-blue); background: var(--ml-blue-pale); }
#ml-app .ml-dz .ml-dz-file { position: absolute; inset: 0; z-index: 2; width: 100% !important; height: 100% !important; max-width: none !important; opacity: 0; cursor: pointer; font-size: 0; }
#ml-app .ml-dz .ml-dz-icon, #ml-app .ml-dz .ml-dz-title, #ml-app .ml-dz .ml-dz-sub { position: relative; z-index: 1; pointer-events: none; }
#ml-app .ml-dz-icon { width: 44px; height: 44px; background: var(--ml-surface2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; }
#ml-app .ml-dz-icon svg { width: 20px; height: 20px; stroke: var(--ml-blue); fill: none; stroke-width: 1.8; stroke-linecap: round; }
#ml-app .ml-dz-title { font-size: 14px; font-weight: 500; color: var(--ml-navy); margin-bottom: 4px; }
#ml-app .ml-dz-sub   { font-size: 12px; color: var(--ml-text3); }
#ml-app .hpm-up-preview { margin-top: 0.75rem; border-radius: 10px; overflow: hidden; border: 0.5px solid var(--ml-border-s); background: #0f1729; }
#ml-app .hpm-up-preview[hidden] { display: none !important; }
#ml-app .hpm-up-preview-inner {
  min-height: 120px; width: 100%; max-height: 200px; display: flex; align-items: center; justify-content: center; box-sizing: border-box;
}
#ml-app .hpm-up-preview-inner img { max-width: 100%; max-height: 200px; width: auto; height: auto; object-fit: contain; display: block; }
#ml-app .hpm-up-preview-inner video {
  width: 100% !important; min-height: 120px; max-height: 200px; object-fit: contain; display: block; background: #000; pointer-events: auto;
}
#ml-app .hpm-up-preview-name { font-size: 12px; color: var(--ml-text2); font-family: var(--ml-fm); padding: 6px 10px; border-top: 0.5px solid var(--ml-border-s); text-align: center; word-break: break-all; }
#ml-app .ml-preview-hero { width: 7rem; height: 10rem; object-fit: cover; border-radius: 10px; border: 0.5px solid var(--ml-border); background: var(--ml-surface2); }
#ml-app .ml-preview-hero video { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 10px; }
#ml-app .ml-thead, #ml-app .ml-row { display: grid; align-items: center; gap: 8px; padding: 10px 16px; }
#ml-app .ml-thead { background: var(--ml-navy); padding: 10px 16px; }
#ml-app .ml-th { font-size: 11px; font-weight: 500; color: rgba(255,255,255,0.55); text-transform: uppercase; letter-spacing: 0.05em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
#ml-app .ml-row { border-top: 0.5px solid var(--ml-border); transition: background .1s; }
#ml-app .ml-row:hover { background: var(--ml-surface); }
#ml-app .ml-file-cell { display: flex; align-items: center; gap: 10px; min-width: 0; }
#ml-app .ml-thumb { width: 40px; height: 40px; border-radius: 7px; flex-shrink: 0; object-fit: cover; background: var(--ml-surface2); border: 0.5px solid var(--ml-border); }
#ml-app .ml-fname { font-size: 13px; font-weight: 500; color: var(--ml-navy); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
#ml-app .ml-fext  { font-size: 11px; color: var(--ml-text3); font-family: var(--ml-fm); }
#ml-app .ml-badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; }
#ml-app .badge-photo { background: var(--ml-blue-pale); color: #1D4ED8; }
#ml-app .badge-video { background: var(--ml-teal-lt);   color: var(--ml-teal); }
#ml-app .ml-size { font-size: 12px; color: var(--ml-text2); font-family: var(--ml-fm); }
#ml-app .ml-date { font-size: 12px; color: var(--ml-text3); }
#ml-app .ml-acts { display: flex; flex-wrap: wrap; gap: 6px; justify-content: flex-end; }
#ml-app .ml-act { font-size: 12px; font-weight: 500; color: var(--ml-blue); text-decoration: none; }
#ml-app .ml-act:hover { text-decoration: underline; }
#ml-app .ml-act-danger { color: #B91C1C; }
#ml-app .ml-empty { padding: 3rem 1rem; text-align: center; }
#ml-app .ml-empty-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--ml-surface2); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
#ml-app .ml-empty-icon svg { width: 22px; height: 22px; stroke: var(--ml-text3); fill: none; stroke-width: 1.5; stroke-linecap: round; }
#ml-app .ml-empty-title { font-size: 14px; font-weight: 500; color: var(--ml-navy); margin-bottom: 4px; }
#ml-app .ml-empty-sub   { font-size: 13px; color: var(--ml-text3); }
#ml-app .ml-breadcrumb { font-size: 12px; color: var(--ml-text2); line-height: 1.4; max-width: 64ch; }
#ml-app .hpm-scope .ml-thead, #ml-app .hpm-scope .ml-row {
  grid-template-columns: minmax(0, 1.5fr) 88px 100px 100px 110px 88px;
}
#ml-app .ml-upload-open {
  display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; background: var(--ml-navy);
  color: #fff; border: none; border-radius: var(--ml-r); font-family: var(--ml-ff); font-size: 13px;
  font-weight: 500; cursor: pointer; transition: background .15s; white-space: nowrap;
}
#ml-app .ml-upload-open:hover { background: var(--ml-navy-h); }
#ml-app .ml-upload-open svg { width: 14px; height: 14px; stroke: #fff; fill: none; stroke-width: 2; }
#ml-app .ml-modal-layer {
  position: fixed; inset: 0; z-index: 10060; display: none; align-items: center; justify-content: center;
  padding: 1rem; background: rgba(10, 22, 40, 0.55);
}
#ml-app .ml-modal-layer.is-open { display: flex; }
#ml-app .ml-modal-box {
  background: var(--ml-white); border: 0.5px solid var(--ml-border); border-radius: 14px; width: 100%;
  max-width: 480px; max-height: 90vh; overflow: auto; box-shadow: 0 20px 50px rgba(0,0,0,.2);
}
#ml-app .ml-modal-hdr {
  display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem;
  border-bottom: 0.5px solid var(--ml-border); background: var(--ml-navy); border-radius: 14px 14px 0 0;
}
#ml-app .ml-modal-hdr h2 { font-size: 15px; font-weight: 500; color: #fff; margin: 0; letter-spacing: -0.01em; }
#ml-app .ml-modal-x {
  background: rgba(255, 255, 255, 0.12); border: none; cursor: pointer; width: 32px; height: 32px; border-radius: 6px;
  color: #fff; font-size: 1.4rem; line-height: 1; display: flex; align-items: center; justify-content: center; transition: background .15s;
}
#ml-app .ml-modal-x:hover { background: rgba(255, 255, 255, 0.22); }
#ml-app .ml-modal-body { padding: 1.25rem 1.5rem; }
#ml-app .ml-modal-foot { display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.25rem; padding-top: 0.5rem; }
#ml-app .ml-btn-ghost {
  padding: 8px 18px; border: 0.5px solid var(--ml-border-s); background: transparent; border-radius: 8px;
  font-family: var(--ml-ff); font-size: 13px; cursor: pointer; color: var(--ml-text2); transition: background .15s;
}
#ml-app .ml-btn-ghost:hover { background: var(--ml-surface2); }
#ml-app .ml-edit-cta { margin-top: 1rem; }
@media (max-width: 900px) {
  #ml-app .hpm-scope .ml-thead, #ml-app .hpm-scope .ml-row { grid-template-columns: 1fr; }
  #ml-app .hpm-scope .ml-thead { display: none; }
  #ml-app .hpm-scope .ml-row { border: 0.5px solid var(--ml-border); border-radius: 8px; margin-bottom: 0.5rem; }
}
#ml-app .ml-edit-grid { display: grid; gap: 1.5rem; grid-template-columns: minmax(0, 8rem) minmax(0, 1fr); align-items: start; }
@media (max-width: 640px) {
  #ml-app .ml-edit-grid { grid-template-columns: 1fr; }
}
</style>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/home-page-media/partials/ml-scoped-styles.blade.php ENDPATH**/ ?>