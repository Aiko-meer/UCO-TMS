const fs=require('fs'), vm=require('vm');
const chartSrc=fs.readFileSync('public/assets/vendor/Chart.min.js','utf8');

function makePair(){
  const calls=[]; const store={};
  const el={width:48,height:48};
  const ctx={
    canvas:el,
    set fillStyle(v){store.fillStyle=v;}, get fillStyle(){return store.fillStyle;},
    set strokeStyle(v){store.strokeStyle=v;}, get strokeStyle(){return store.strokeStyle;},
    set lineWidth(v){store.lineWidth=v;}, get lineWidth(){return store.lineWidth;},
    beginPath(){}, closePath(){}, moveTo(){}, lineTo(){},
    arc(x,y,r,s,e){calls.push({op:'arc',fill:store.fillStyle,x,y,r,s,e});},
    fill(){calls.push({op:'fill',style:store.fillStyle});},
    stroke(){calls.push({op:'stroke',style:store.strokeStyle});},
    save(){}, restore(){}, translate(){}, rotate(){}, scale(){},
    measureText(){return{width:10};}, fillText(){}, clearRect(){}, setTransform(){},
    drawImage(){}, putImageData(){}, createLinearGradient(){return{addColorStop(){}};},
    getImageData(){return{data:[0,0,0,0]};}, bezierCurveTo(){}, quadraticCurveTo(){},
    rect(){}, arcTo(){}, ellipse(){}, clip(){}, font:'', textAlign:'', textBaseline:''
  };
  el.getContext=()=>ctx;
  return {el,ctx,calls};
}

function run(percentage,color){
  const {el,ctx,calls}=makePair();
  const sandbox={console};
  sandbox.window=sandbox; sandbox.global=sandbox; sandbox.document={};
  sandbox.navigator={userAgent:'node'};
  vm.createContext(sandbox);
  vm.runInContext(chartSrc,sandbox,{filename:'Chart.min.js'});
  new sandbox.Chart(el,{type:'doughnut',
    data:{datasets:[{data:[percentage,100-percentage],backgroundColor:[color,'#e9ecef'],borderWidth:0}]},
    options:{cutoutPercentage:80,tooltips:{enabled:false},responsive:false,maintainAspectRatio:false}});
  const arcs=calls.filter(c=>c.op==='arc');
  const fills=calls.filter(c=>c.op==='fill').map(c=>c.style);
  return {arcs,fills};
}

for (const [p,c] of [[0,'#ffc107'],[0,'#007bff'],[0,'#28a745'],[40,'#ffc107'],[100,'#28a745'],[50,'#007bff']]) {
  const {arcs,fills}=run(p,c);
  const span=(a)=>((a.e-a.s)*180/Math.PI).toFixed(1)+'deg';
  console.log(`pct=${String(p).padStart(3)} color=${c} -> arcs:` +
    arcs.map((a,i)=>` [arc${i} ${span(a)} fill=${a.fill}]`).join('') + ` fills=${JSON.stringify(fills)}`);
}
