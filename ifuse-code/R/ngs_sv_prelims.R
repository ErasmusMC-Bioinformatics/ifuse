
############################################################
##PRELIMINARY FUNCTIONS#####################################
############################################################


## find start and end positions of junction sequence
startend<-function(data){
	data$LeftStart<-NA
	data$LeftEnd<-NA
	data$RightStart<-NA
	data$RightEnd<-NA

	for(i in 1:nrow(data)){
		if(data$LeftStrand[i]=="+"){
			data$LeftStart[i]<-data$LeftPosition[i]-data$LeftLength[i]
			data$LeftEnd[i]<-data$LeftPosition[i]
		} else {
			data$LeftStart[i]<-data$LeftPosition[i]
			data$LeftEnd[i]<-data$LeftPosition[i]+data$LeftLength[i]
		}
		if(data$RightStrand[i]=="+"){
			data$RightStart[i]<-data$RightPosition[i]
			data$RightEnd[i]<-data$RightPosition[i]+data$RightLength[i]
		} else {
			data$RightStart[i]<-data$RightPosition[i]-data$RightLength[i]
			data$RightEnd[i]<-data$RightPosition[i]
		}
	}

return(data)
}


## fills up ragged arrays in a list
fillup<-function(list1){
	maxLen1 <- max(sapply(list1, length))
	newM1 <- lapply(list1, function(.ele){
		c(.ele, rep(NA, maxLen1))[1:maxLen1]})
	filled<-do.call(rbind, newM1)

return(filled)
}


## find genes containing left position
subsetintleft<-function(x,y){
	## x is  element of list of ucsc tables per chromosome
	## y is input data with start and ends
	leftkeep<-list()
	chrmatch<-y[y$LeftChr==unique(x$chrom),]

	if(nrow(chrmatch)>0){
		for(i in 1:nrow(chrmatch)){
			if(chrmatch$LeftStrand[i]=="+"){
				lk<-x[which(x$txStart<chrmatch$LeftEnd[i] &
				x$txEnd>chrmatch$LeftEnd[i]),]
				## finding which intervals surround the left position
			} else lk<-x[which(x$txStart<chrmatch$LeftStart[i] & x$txEnd>chrmatch$LeftStart[i]),]
			if(length(grep("NR",lk[,2]))>0) lk<-lk[-c(grep("NR",lk[,2])),]
			if(nrow(lk)>1) lk<-lk[-c(grep("NM_0011",lk[,2])),]
			if(nrow(lk)>1 & length(unique(lk$name2))==1) lk<-lk[1,]
			leftkeep[[i]]<-lk
		}
		names(leftkeep)<-row.names(chrmatch)
		leftkeep<-do.call(rbind,leftkeep)
		colnames(leftkeep)<-paste("Left",colnames(leftkeep),sep=".")
	} else leftkeep<-NA

return(leftkeep)
}


## find genes containing right position
subsetintright<-function(x,y){
	## x is  element of list of ucsc tables per chromosome
	## y is input data with start and ends
	rightkeep<-list()
	chrmatch<-y[y$LeftChr==unique(x$chrom),]

	if(nrow(chrmatch)>0){
		for(i in 1:nrow(chrmatch)){
			if(chrmatch$RightStrand[i]=="+"){
				rk<-x[which(x$txStart<chrmatch$RightStart[i] &
				x$txEnd>chrmatch$RightStart[i]),]
				## finding which intervals surround the right position
			} else rk<-x[which(x$txStart<chrmatch$RightEnd[i] & x$txEnd>chrmatch$RightEnd[i]),]
			if(length(grep("NR",rk[,2]))>0) rk<-rk[-c(grep("NR",rk[,2])),]
			if(nrow(rk)>1) rk<-rk[-c(grep("NM_0011",rk[,2]),grep("NR",rk[,2])),]
			if(nrow(rk)>1 & length(unique(rk$name2))==1) rk<-rk[1,]
			rightkeep[[i]]<-rk
		}
		names(rightkeep)<-row.names(chrmatch)
		rightkeep<-do.call(rbind,rightkeep)
		colnames(rightkeep)<-paste("Right",colnames(rightkeep),sep=".")
		} else rightkeep<-NA

return(rightkeep)
}


## find associated junctions within gene and per chromosome
cjunct<-function(y,genes,cdsinfo){
	## y = junctions
	y<-y[,c(1:4,6:8)]
	x<-cbind(genes[,-1],cdsinfo[,c(3,4,6,7,9,14,15,17,18,20)])
	z<-cbind(y,x)
	matches<-list()

	for(i in 1:nrow(z)){
		m1<-unique(z[which((z$LeftPosition>z$Left.txStart[i]&z$LeftPosition<z$Left.txEnd[i]) &
			(levels(z$LeftChr)[z$LeftChr]==levels(z$Left.chrom[i])[z$Left.chrom[i]])),c(1:7,8:12,18:22)])
			## only left genes
		m2<-unique(z[which((z$RightPosition>z$Right.txStart[i]&z$RightPosition<z$Right.txEnd[i]) &
			(levels(z$RightChr)[z$RightChr]==levels(z$Right.chrom[i])[z$Right.chrom[i]])),c(1:7,13:17,23:27)])
			## only right genes
		n<-x[i,]
		if(nrow(m1)==0){
			m1[1:nrow(m2),]<-NA
			m1[,1:7]<-m2[,1:7]
		}
		if(nrow(m2)==0){
			m2[1:nrow(m1),]<-NA
			m2[,1:7]<-m1[,1:7]
		}
		mlr<-merge(m1,m2,by=1:7)
		matches[[i]]<-mlr[!is.na(mlr[,1]),]
		if(nrow(matches[[i]])!=0) matches[[i]]$associated<-paste("aj",i,sep="")
	}

	matchfirst<-do.call(rbind,matches)
	matcheds1<-matchfirst[order(matchfirst$Left.name2),]
	matcheds2<-matcheds1[order(matcheds1$Right.name2),]
	if(nrow(matcheds2)>0){
		nodup<-which(duplicated(matcheds2[,-28]))
		return(matcheds2[-nodup,])
	} else{
		z$associated<-NA
		return(z)
	}

}


## find junctions that share genes
gjunct<-function(matched){
	lri<-list()

	for(i in 1:nrow(matched)){
		ln<-matched$Left.name2[i]
		rn<-matched$Right.name2[i]
		if(!is.na(ln) | !is.na(rn)){
			t1<-matched[which((matched$Left.name2==ln
				| matched$Right.name2==ln) | (matched$Left.name2==rn
				| matched$Right.name2==rn)),]
			t2<-c(t1$Left.name2,t1$Right.name2)
			t2<-t2[!is.na(t2)]
			lri[[i]]<-which(matched$Left.name2%in%t2 | matched$Right.name2%in%t2)
		} else lri[[i]]<-i
	}

	#lri<-unique(lri[!is.na(lri)])
	names(lri)<-paste("sg",1:length(lri),sep="")
	lri.df<-fillup(lri)
	lri.df<-unique(lri.df)
	sg<-c()
	for(k in 1:nrow(lri.df)){
		x<-lri.df[k,]
		x<-x[!is.na(x)]
		sg[x]<-row.names(lri.df)[k]
	}
	matched$sharedgenes<-sg

return(matched)
}


## annotate genes/junctions with event calls
annotate<-function(data){
	genemismatch<-c()
	singleevent<-c()
	fusiongene<-c()

	for(i in 1:nrow(data)){
		if(!is.na(data$Left.name2[i]) & !is.na(data$Right.name2[i])){
			if(data$Left.name2[i]==data$Right.name2[i]){
				genemismatch[i]<-"no"
			} else genemismatch[i]<-"yes"
		} else genemismatch[i]<-"NA"

		if(data$LeftStrand[i]=="+" & data$RightStrand[i]=="+"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"deletion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}

		if(data$LeftStrand[i]=="+"&data$RightStrand[i]=="-"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}

		if(data$LeftStrand[i]=="-"&data$RightStrand[i]=="+"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}

		if(data$LeftStrand[i]=="-"&data$RightStrand[i]=="-"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion or translocation"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}
	}
	data$genemismatch<-genemismatch
	data$singleevent<-singleevent
	data$fusiongene<-fusiongene

return(data)
}


## matching function for related junctions (see below)
matchfun<-function(x){
	matchlocs<-list()
	for(m in 1:length(x)){
		matchloc<-c()
		for(j in 1:length(x)){
			if(any(x[[j]]%in%x[[m]])){
				matchloc<-c(matchloc,j)
			}
		}
		matchlocs[[m]]<-matchloc
	}

return(matchlocs)
}


## find related junctions
rjunct<-function(z,distance){
	matches<-list()

	for(i in 1:nrow(z)){
		m1<-which((abs(z$'Junction LeftPosition'-z$'Junction LeftPosition'[i])<distance) &
			(z$'Junction LeftChr'==z$'Junction LeftChr'[i]))
		m2<-which((abs(z$'Junction RightPosition'-z$'Junction RightPosition'[i])<distance) &
			(z$'Junction RightChr'==z$'Junction RightChr'[i]))
		m3<-which((abs(z$'Junction RightPosition'-z$'Junction LeftPosition'[i])<distance) &
			(z$'Junction RightChr'==z$'Junction LeftChr'[i]))
		matches[[i]]<-sort(unique(c(m1,m2,m3)))
	}

	matchlocs1<-matchfun(matches)
	matchlocs2<-matchfun(matchlocs1)
	umatches<-unique(matchlocs2)
	related<-c()
	for(k in 1:length(umatches)){
		x<-umatches[[k]]
		related[x]<-paste("rj",k,sep="")
	}
	z$'Related Junctions'<-related

return(z)
}

## find which junctions are in exons
exonannot<-function(allwithint){
	exoncds<-list()

	for(i in 1:nrow(allwithint)){
		x<-allwithint[i,]
		leftposincds<-rightposincds<-leftposinexon<-rightposinexon<-c()
		if(!is.na(x[16])){
			if(x[29] >= x[14] & x[29] <= x[15]){
				leftposincds<-"yes"
			} else leftposincds<-"no" #14 15 #left cds #29 30 31 #left position, start, end
		lexon<-x[16:17] #16 17 #left exon
		lexon_se<-cbind(strsplit(as.matrix(lexon[1]),"\\,")[[1]],strsplit(as.matrix(lexon[2]),"\\,")[[1]])
		lexon_se<-matrix(as.numeric(lexon_se),ncol=2)
		leftiexon<-c()
		for(j in 1:nrow(lexon_se)){
			if(x[29] >= lexon_se[j,1] & x[29] <= lexon_se[j,2]){
				leftiexon[j]<-T
			} else leftiexon[j]<-F
		}
		if(any(leftiexon==T)) leftposinexon<-"yes" else leftposinexon<-"no"
		} else leftposinexon<-leftposincds<-NA

		if(!is.na(x[26])){
			if(x[34] >= x[23] & x[34] <= x[24]){
				rightposincds<-"yes"
			} else rightposincds<-"no" #23 24 #right cds	#34 35 36 #right position, start, end
		rexon<-x[25:26] #25 26 #right exon
		rexon_se<-cbind(strsplit(as.matrix(rexon[1]),"\\,")[[1]],strsplit(as.matrix(rexon[2]),"\\,")[[1]])
		rexon_se<-matrix(as.numeric(rexon_se),ncol=2)
		rightiexon<-c()
		for(j in 1:nrow(rexon_se)){
			if(x[34] >= rexon_se[j,1] & x[34] <= rexon_se[j,2]){
				rightiexon[j]<-T
			} else rightiexon[j]<-F
		}
		if(any(rightiexon==T)) rightposinexon<-"yes" else rightposinexon<-"no"
		} else rightposinexon<-rightposincds<-NA
		exoncds[[i]]<-cbind(leftposincds,rightposincds,leftposinexon,rightposinexon)
	}

	exoncds_df<-do.call(rbind,exoncds)
	colnames(exoncds_df)<-c("Left Position in CDS","Right Position in CDS","Left Position in Exon", "Right Position in Exon")

return(exoncds_df)
}

############################################################
############################################################
