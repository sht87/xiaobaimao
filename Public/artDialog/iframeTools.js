/*!
 * artDialog iframeTools
 * Date: 2011-12-08 1:32
 * http://code.google.com/p/artdialog/
 * (c) 2009-2011 TangBin, http://www.planeArt.cn
 *
 * This is licensed under the GNU LGPL, version 2.1 or later.
 * For details, see: http://creativecommons.org/licenses/LGPL/2.1/
 */
eval(function (B, D, A, G, E, F) { function C(A) { return A < 62 ? String.fromCharCode(A += A < 26 ? 65 : A < 52 ? 71 : -4) : A < 63 ? '_' : A < 64 ? '$' : C(A >> 6) + C(A & 63) } while (A > 0) E[C(G--)] = D[--A]; return B.replace(/[\w\$]+/g, function (A) { return E[A] == F[A] ? A : E[A] }) }('(6(E,C,D,A){c B,X,W,J="@_.DATA",K="@_.OPEN",H="@_.OPENER",I=C.k=C.k||"@_.WINNAME"+(Bd Bo).Be(),F=C.VBArray&&!C.XMLHttpRequest;E(6(){!C.Bu&&7.BY==="B0"&&Br("9 Error: 7.BY === \\"B0\\"")});c G=D.d=6(){c W=C,X=6(A){f{c W=C[A].7;W.BE}u(X){v!V}v C[A].9&&W.BE("frameset").length===U};v X("d")?W=C.d:X("BB")&&(W=C.BB),W}();D.BB=G,B=G.9,W=6(){v B.BW.w},D.m=6(C,B){c W=D.d,X=W[J]||{};W[J]=X;b(B!==A)X[C]=B;else v X[C];v X},D.BQ=6(W){c X=D.d[J];X&&X[W]&&1 X[W]},D.through=X=6(){c X=B.BR(i,BJ);v G!==C&&(D.B4[X.0.Z]=X),X},G!==C&&E(C).BN("unload",6(){c A=D.B4,W;BO(c X BS A)A[X]&&(W=A[X].0,W&&(W.duration=U),A[X].s(),1 A[X])}),D.p=6(B,O,BZ){O=O||{};c N,L,M,Bc,T,S,R,Q,BF,P=D.d,Ba="8:BD;n:-Bb;d:-Bb;Bp:o U;Bf:transparent",BI="r:g%;x:g%;Bp:o U";b(BZ===!V){c BH=(Bd Bo).Be(),BG=B.replace(/([?&])W=[^&]*/,"$1_="+BH);B=BG+(BG===B?(/\\?/.test(B)?"&":"?")+"W="+BH:"")}c G=6(){c B,C,W=L.2.B2(".aui_loading"),A=N.0;M.addClass("Bi"),W&&W.hide();f{Q=T.$,R=E(Q.7),BF=Q.7.Bg}u(X){T.q.5=BI,A.z?N.z(A.z):N.8(A.n,A.d),O.j&&O.j.l(N,Q,P),O.j=By;v}B=A.r==="Bt"?R.r()+(F?U:parseInt(E(BF).Bv("marginLeft"))):A.r,C=A.x==="Bt"?R.x():A.x,setTimeout(6(){T.q.5=BI},U),N.Bk(B,C),A.z?N.z(A.z):N.8(A.n,A.d),O.j&&O.j.l(N,Q,P),O.j=By},I={w:W(),j:6(){N=i,L=N.h,Bc=L.BM,M=L.2,T=N.BK=P.7.Bn("BK"),T.Bx=B,T.k="Open"+N.0.Z,T.q.5=Ba,T.BX("frameborder",U,U),T.BX("allowTransparency",!U),S=E(T),N.2().B3(T),Q=T.$;f{Q.k=T.k,D.m(T.k+K,N),D.m(T.k+H,C)}u(X){}S.BN("BC",G)},s:6(){S.Bv("4","o").unbind("BC",G);b(O.s&&O.s.l(i,T.$,P)===!V)v!V;M.removeClass("Bi"),S[U].Bx="about:blank",S.remove();f{D.BQ(T.k+K),D.BQ(T.k+H)}u(X){}}};Bq O.Y=="6"&&(I.Y=6(){v O.Y.l(N,T.$,P)}),Bq O.y=="6"&&(I.y=6(){v O.y.l(N,T.$,P)}),1 O.2;BO(c J BS O)I[J]===A&&(I[J]=O[J]);v X(I)},D.p.Bw=D.m(I+K),D.BT=D.m(I+H)||C,D.p.origin=D.BT,D.s=6(){c X=D.m(I+K);v X&&X.s(),!V},G!=C&&E(7).BN("mousedown",6(){c X=D.p.Bw;X&&X.w()}),D.BC=6(C,D,B){B=B||!V;c G=D||{},H={w:W(),j:6(A){c W=i,X=W.0;E.ajax({url:C,success:6(X){W.2(X),G.j&&G.j.l(W,A)},cache:B})}};1 D.2;BO(c F BS G)H[F]===A&&(H[F]=G[F]);v X(H)},D.Br=6(B,A){v X({Z:"Alert",w:W(),BL:"warning",t:!U,BA:!U,2:B,Y:!U,s:A})},D.confirm=6(C,A,B){v X({Z:"Confirm",w:W(),BL:"Bm",t:!U,BA:!U,3:U.V,2:C,Y:6(X){v A.l(i,X)},y:6(X){v B&&B.l(i,X)}})},D.prompt=6(D,B,C){C=C||"";c A;v X({Z:"Prompt",w:W(),BL:"Bm",t:!U,BA:!U,3:U.V,2:["<e q=\\"margin-bottom:5px;font-Bk:12px\\">",D,"</e>","<e>","<Bl B1=\\"",C,"\\" q=\\"r:18em;Bh:6px 4px\\" />","</e>"].join(""),j:6(){A=i.h.2.B2("Bl")[U],A.select(),A.BP()},Y:6(X){v B&&B.l(i,A.B1,X)},y:!U})},D.tips=6(B,A){v X({Z:"Tips",w:W(),title:!V,y:!V,t:!U,BA:!V}).2("<e q=\\"Bh: U 1em;\\">"+B+"</e>").time(A||V.B6)},E(6(){c A=D.dragEvent;b(!A)v;c B=E(C),X=E(7),W=F?"BD":"t",H=A.prototype,I=7.Bn("e"),G=I.q;G.5="4:o;8:"+W+";n:U;d:U;r:g%;x:g%;"+"cursor:move;filter:alpha(3=U);3:U;Bf:#FFF",7.Bg.B3(I),H.Bj=H.Bs,H.BV=H.Bz,H.Bs=6(){c E=D.BP.h,C=E.BM[U],A=E.2[U].BE("BK")[U];H.Bj.BR(i,BJ),G.4="block",G.w=D.BW.w+B5,W==="BD"&&(G.r=B.r()+"a",G.x=B.x()+"a",G.n=X.scrollLeft()+"a",G.d=X.scrollTop()+"a"),A&&C.offsetWidth*C.offsetHeight>307200&&(C.q.BU="hidden")},H.Bz=6(){c X=D.BP;H.BV.BR(i,BJ),G.4="o",X&&(X.h.BM[U].q.BU="visible")}})})(i.art||i.Bu,i,i.9)', 'P|R|T|U|V|W|0|1|_|$|ok|id|px|if|var|top|div|try|100|DOM|this|init|name|call|data|left|none|open|style|width|close|fixed|catch|return|zIndex|height|cancel|follow|config|delete|content|opacity|display|cssText|function|document|position|artDialog|ARTDIALOG|contentWindow|lock|parent|load|absolute|getElementsByTagName|S|Y|Z|a|arguments|iframe|icon|main|bind|for|focus|removeData|apply|in|opener|visibility|_end|defaults|setAttribute|compatMode|O|Q|9999em|X|new|getTime|background|body|padding|aui_state_full|_start|size|input|question|createElement|Date|border|typeof|alert|start|auto|jQuery|css|api|src|null|end|BackCompat|value|find|appendChild|list|3|5'.split('|'), 109, 122, {}, {}))

var XB = {
    //验证是数字 非数字返回True
    CheckNum: function (Val) {
        var re = /^-?[1-9]+(\.\d+)?$|^-?0(\.\d+)?$|^-?[1-9]+[0-9]*(\.\d+)?$/;
        return !re.test(Val);
    },
    //验证是邮箱格式 不合法返回True
    CheckEmail: function (Val) {
        var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return !re.test(Val);
    },
    //必须包含“数字”,“字母”,“特殊字符”两种以上的正则表达式
    Checkchar: function (Val) {
        var re = /^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{6,20}$/;
        return !re.test(Val);
    },
    //验证是电话
    CheckPhone: function (Val) {
        var re = /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
        return !re.test(Val);
    },
    //验证正整数，含0 非正整数返回True
    CheckZNum: function (Val) {
        var re = /^([1-9][0-9]*)$/;
        return !re.test($.trim(Val));
    },
    //验证非负浮点数，含0 其他返回true
    CheckZFloat: function (Val) {
        var re = /^\d+(\.\d+)?$/; //未解决0开头的如：0123
        return !re.test($.trim(Val));
    },
    //验证汉字，非汉字返回true
    CheckHZ: function (Val, Lower, Caps) {
        var re = new RegExp("^[\u4e00-\u9fa5]{" + Lower + "," + Caps + "}$");
        return !re.test($.trim(Val));
    },
    //验证拼音字母，中间不空格，非返回true
    CheckZM: function (Val, Lower, Caps) {
        var re = new RegExp("^[a-zA-Z]{" + Lower + "," + Caps + "}$");
        return !re.test($.trim(Val));
    },
    //验证网址 非网址返回True
    CheckURL: function (Val) {
        var re = /^(\w+:\/\/)?\w+(\.\w+)+.*$/;
        return !re.test($.trim(Val));
    },
    //验证手机 不合法返回True
    CheckMobile: function (Val) {
        var re = /^((1[3-8][0-9]{1})+\d{8})$/;
        return !re.test($.trim(Val));
    },
    CheckZip: function (Val) {
        var re = /^[1-9][0-9]{5}$/;
        return !re.test($.trim(Val));
    },

    //验证QQ 不合法返回True
    CheckQQ: function (Val) {
        var re = /^[1-9][0-9]{4,}$/;
        return !re.test($.trim(Val));
    },
    //小数进位
    ValJW: function (d, len) {
        var LS = numMulti(d, Math.pow(10, len));
        if (parseInt(LS) == LS) return d;
        d = d.toString();
        LS = d.substr(d.indexOf(".") + 1, len);
        LS = numAdd(parseInt(d), numDiv((parseInt(LS) + 1), Math.pow(10, len)));
        return LS;
    },
    //验证IP,非IP返回真
    CheckIP: function (value) {
        var ip = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        return !(ip.test(value) && (RegExp.$1 < 256 && RegExp.$2 < 256 && RegExp.$3 < 256 && RegExp.$4 < 256));
    },
    //错误提醒 Tips提醒内容 F关闭事件 ok确定事件
    Error: function (Tips, ok,F) {
        if (F == undefined) {
            F = function () { }
        }
        if (ok == undefined) {
            ok = function () { }
        }
        if (F != undefined&&ok==undefined) {
            ok = F;
        }
        if (ok != undefined && F == undefined) {
            F= ok;
        }
        art.dialog({
            title: '错误提示',
            content: Tips,
            lock: true,
            fixed: true,
            icon: "error",
            time:6,
            close: F,
            ok: ok
        });
    },
    //正确提醒 Tips提醒内容 F关闭事件 ok确定事件  
    Success: function (Tips, ok,F) {
        if (F == undefined) {
            F = function () { }
        }
        if (ok == undefined) {
            ok = function () { }
        }
        if (F != undefined && ok == undefined) {
            ok = F;
        }
        if (ok != undefined && F == undefined) {
            F = ok;
        }
        art.dialog({
            title: '成功提示',
            content: Tips,
            lock: true,
            fixed: true,
            icon: "succeed",
            time:6,
            close: F,
            ok: ok
        });
    },
    //温馨提示 Tips提醒内容 F关闭事件 ok确定事件
    Tip: function (Tips, ok,F,w) {
        if (F == undefined) {
            F = function () { }
        }
        if (ok == undefined) {
            ok = function () { }
        }
        if (F != undefined && ok == undefined) {
            ok = F;
        }
        if (ok != undefined && F == undefined) {
            F = ok;
        }
        if (w == undefined) {
            art.dialog({
                title: '温馨提示',
                content: Tips,
                lock: true,
                fixed: true,
                icon: "warning",
                time:6,
                close: F,
                ok: ok
            });
        } else {
            art.dialog({
                title: '温馨提示',
                content: Tips,
                width: w,
                lock: true,
                fixed: true,
                icon: "warning",
                time:6,
                close: F,
                ok: ok
            });
        }
    },

    msg: function (Tips) {

        art.dialog({
            title: '',
            content: Tips,
            lock: true,
            fixed: true,
            icon: "face-smile",
            time:3,
        });
    },

    //确定取消提醒 Tips提醒内容 okValue确定文字 cancelValue取消提示文字 F关闭事件 ok确定事件 cancel取消事件
    dialog: function (Tips, ok, cancel,F, okValue, cancelValue) {
        if (F == undefined) {
            F = function () { }
        }
        if (ok == undefined) {
            ok = function () { }
        }
        if (cancel == undefined) {
            cancel = function () { }
        }
        if (okValue == undefined) {
            okValue = "确定";
        }
        if (cancelValue == undefined) {
            cancelValue = "取消";
        }
        art.dialog({
            title: '警告',
            content: Tips,
            lock: true,
            fixed: true,
            icon: "warning",
            close: F,
            okValue: okValue,
            ok: ok,
            cancelValue:cancelValue,
            cancel: cancel
        });
    },
    Prompt: function (content, yes, value, tip) {
        tip = tip == "" ? "消息" : tip;
        value = value || '';  
        var input;  
        return artDialog({  
            id: 'Prompt',  
            icon: 'question',  
            fixed: true,  
            lock: true,
            title: tip,
            opacity: .1,  
            content: [  
                '<div style="margin-bottom:5px;font-size:12px">',  
                    content,  
                '</div>',  
                '<div>',  
                    '<input value="',  
                        value,  
                    '" style="width:18em;padding:6px 4px;border: 1px solid #ccc;" />',
                '</div>'  
            ].join(''),  
            init: function () {  
                input = this.DOM.content.find('input')[0];  
                input.select();  
                input.focus();  
            },  
            ok: function (here) {
                return yes && yes.call(this, input.value, here);  
            },  
            cancel: true  
        });  
    },
    Open: function (Tips,url,w,h,close) {
        if (w == undefined) {
            w = "500px";
        }
        if (h == undefined) {
            h=  "300px";
        }
        if (close == undefined) {
            close = function () { }
        }
        art.dialog.open(url, {
            esc : false,
            lock : true,
            title : Tips,
            width : w,
            height : h,
            close: close
        });
    },//art.dialog.close();在当前页面关闭自己
    CheckAjax: function (Name, Url, Para, Tip) {
        var text = ['', '不能为空', '被他人占用', '不正确'];
        var ReturnVal = true;
        $.ajax({
            url: Url,
            data: Para,
            type: "POST",
            async:false,
            success: function (data) {
                if (data.status == 0) {
                    $(Tip).removeClass().addClass('Success').text('').text(data.message);
                }
                else if (data.status == -1) {
                    ReturnVal = false;
                    $(Tip).removeClass().addClass('Warn').text('').text(data.message);
                }
                else {
                    ReturnVal = false;
                    $(Tip).removeClass().addClass('Warn').text('').text(Name + text[data.status]);
                }
            }
        });
        return ReturnVal;
    },
    CheckAjax2: function (Name, Url, Para, Tip) {
        var text = ['', '不能为空', '被他人占用', '不正确'];
        var ReturnVal = true;
        $.ajax({
            url: Url,
            data: Para,
            type: "POST",
            async: false,
            success: function (data) {
                if (data.status == 0) {
                    $(Tip).removeClass().text('');
                }
                else if (data.status == -1) {
                    ReturnVal = false;
                    $(Tip).removeClass().addClass('Warn').text('').text(data.message);
                }
                else {
                    ReturnVal = false;
                    $(Tip).removeClass().addClass('Warn').text('').text(Name + text[data.status]);
                }
            }
        });
        return ReturnVal;
    },
    CheckAjax3: function (Url, Para) {
        var text = ['', '不能为空', '被他人占用', '不正确'];
        var ReturnVal;
        $.ajax({
            url: Url,
            data: Para,
            type: "POST",
            async: false,
            success: function (data) {
                ReturnVal = data;
            }
        });
        return ReturnVal;
    }
}